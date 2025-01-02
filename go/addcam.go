package main

import (
	"database/sql"
	"fmt"
	"os"
	"strconv"
)

func addcam(args AddCameraArgs, db *sql.DB) int64 {
	exif := getExif(args.imgPath) //Get EXIF data
	//Check if camera already exists in DB
	if checkIfExistsInDB("digitalCameras", "serial", strconv.Itoa(exif.SerialNumber), db) {
		fmt.Println("Camera serial already in database")
		os.Exit(1)
	}
	dbInfo := writeCamDBInfo(args, exif) //Write DBInfo
	id := addcamDB(dbInfo, db)           //Write DBInfo to DB
	return id
}

func addcamDB(dbInfo camDBInfo, db *sql.DB) int64 {
	//Add camera to DB
	result, err := db.Exec("INSERT INTO digitalCameras (serial, manufacturer, model, imageWidth, imageHeight) VALUES (?, ?, ?, ?, ?)",
		dbInfo.serial,
		dbInfo.manufacturer, dbInfo.model,
		dbInfo.imageWidth, dbInfo.imageHeight)
	errCheckDB(err)
	//Get ID of new DB entry
	id, err := result.LastInsertId()
	errCheckDB(err)
	return id
}

func writeCamDBInfo(args AddCameraArgs, exif ExifData) camDBInfo {
	var dbInfo camDBInfo
	//Correct width & height in case of a portrait input image
	exifX, exifY := correctWidthHeight(exif.ImageWidth, exif.ImageHeight)
	//Set DBInfo values to exif values unless args are set
	setValInt(args.serial, exif.SerialNumber, &dbInfo.serial)
	setValStr(args.manufacturer, exif.Make, &dbInfo.manufacturer)
	setValStr(args.model, exif.Model, &dbInfo.model)
	setValInt(args.imageWidth, exifX, &dbInfo.imageWidth)
	setValInt(args.imageHeight, exifY, &dbInfo.imageHeight)

	return dbInfo
}

func correctWidthHeight(x, y int) (int, int) {
	var width, height int
	if x > y {
		width = x
		height = y
	} else {
		width = y
		height = x
	}
	return width, height
}
