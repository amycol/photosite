package main

import (
	"database/sql"
	"fmt"
	"os"
)

func addlens(args AddLensArgs, db *sql.DB) int64 {
	exif := getExif(args.imgPath) //Get EXIF data
	//Check if lens exists in DB
	if checkIfExistsInDB("lenses", "serial", exif.LensSerialNumber, db) {
		fmt.Println("Lens serial already in database")
		os.Exit(1)
	}
	dbInfo := writeLensDBInfo(args, exif) //Write DBinfo
	id := addlensDB(dbInfo, db)           //Write DBInfo to DB
	return id
}

func addlensDB(dbInfo lensDBInfo, db *sql.DB) int64 {
	//Add lens to DB
	result, err := db.Exec("INSERT INTO lenses (serial, manufacturer, name, prime, minFocalLength, maxFocalLength, minAperture, maxAperture, mount, blades, autofocus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
		dbInfo.serial, dbInfo.manufacturer,
		dbInfo.name, dbInfo.prime,
		dbInfo.minFocalLength, dbInfo.maxFocalLength,
		dbInfo.minAperture, dbInfo.maxAperture,
		dbInfo.mount, dbInfo.blades,
		dbInfo.autofocus)
	errCheckDB(err)
	//Get ID of new DB entry
	id, err := result.LastInsertId()
	errCheckDB(err)
	return id
}

func writeLensDBInfo(args AddLensArgs, exif ExifData) lensDBInfo {
	var dbInfo lensDBInfo
	//Process Focal Length EXIF strings
	minFocalLengthExif := getFocalLengthInt(exif.MinFocalLength)
	maxFocalLengthExif := getFocalLengthInt(exif.MaxFocalLength)
	//Set DBInfo values to exif values unless args are set
	setValStr(args.serial, exif.LensSerialNumber, &dbInfo.serial)
	setValStr(args.name, exif.LensType, &dbInfo.name)
	setValInt(args.minFocalLength, minFocalLengthExif, &dbInfo.minFocalLength)
	setValInt(args.maxFocalLength, maxFocalLengthExif, &dbInfo.maxFocalLength)
	setValFlt(args.minAperture, exif.MinAperture, &dbInfo.minAperture)
	setValFlt(args.maxAperture, exif.MaxAperture, &dbInfo.maxAperture)
	//Set leftover DBInfo values
	dbInfo.manufacturer = args.manufacturer
	dbInfo.prime = (dbInfo.minFocalLength == dbInfo.maxFocalLength)
	dbInfo.mount = args.mount
	dbInfo.blades = args.blades
	dbInfo.autofocus = args.autofocus

	return dbInfo
}
