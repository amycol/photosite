package main

import (
	"database/sql"
	"fmt"
	"os"
	"strconv"
	"strings"
)

func addimg(args AddImageArgs, db *sql.DB) int64 {
	exif := getExif(args.imgPath)            //Get EXIF data
	newargs := *processArgs(args, exif)      //Process some args to allow default values
	dbInfo := writeimgDBInfo(exif, args, db) //Write imgDBInfo struct

	processImage(newargs, exif) //Process the image

	id := writeToDB(dbInfo, db) //Write imgDBInfo to DB
	outputImg(id, args.outDir)  //Output the image files
	return id
}

// Writes info for db (list on notes)
func writeimgDBInfo(exif ExifData, args AddImageArgs, db *sql.DB) imgDBInfo {
	var dbInfo imgDBInfo
	//Process ShutterSpeed EXIF string and add to imgDBInfo
	var shutter []string
	if strings.Contains(exif.Shutter, "/") { //String is a fraction and needs processing
		shutter = strings.Split(exif.Shutter, "/")
	} else { //String is a whole number and needs to be converted to a fraction
		shutter = append(shutter, exif.Shutter)
		shutter = append(shutter, "1")
	}
	shutterFloat640, err := strconv.ParseFloat(shutter[0], 32) //Convert strings to 32-bit float64
	shutterFloat641, err := strconv.ParseFloat(shutter[1], 32)
	dbInfo.shutterSpeedNumerator = float32(shutterFloat640)   //Convert 32-bit float64 to float32
	dbInfo.shutterSpeedDenominator = float32(shutterFloat641) //Convert 32-bit float64 to float32
	errCheckConv(err)
	//Process Focal Length EXIF string and add to imgDBInfo
	fl := getFocalLengthInt(exif.FocalLength)
	dbInfo.focalLength = fl
	//Process Flash EXIF string and add to imgDBInfo
	if strings.Contains(exif.Flash, "Fired") {
		dbInfo.flash = true
	} else {
		dbInfo.flash = false
	}
	//Process date & time and add to imgDBInfo
	datetime := strings.Split(exif.DateTimeOriginal, " ")
	dbInfo.time = datetime[1]
	date := datetime[0]
	date = strings.Replace(date, ":", "-", 2)
	dbInfo.date = date
	//Add other data to imgDBInfo
	dbInfo = dbInfoIDs(dbInfo, exif, args, db) //Add IDs to imgDBInfo
	dbInfo.aperture = exif.FNumber             //Add aperture to imgDBInfo
	dbInfo.iso = exif.ISO                      //Add ISO to imgDBInfo
	dbInfo.mode = exif.ExposureMode            //Add Exposure Mode to imgDBInfo
	dbInfo.oldFilename = exif.FileName         //Add old (before renaming to DB ID) filename to imgDBInfo
	dbInfo.extraInfo = args.extraInfo          //Add extra info to imgDBInfo
	return dbInfo
}

func dbInfoIDs(dbInfo imgDBInfo, exif ExifData, args AddImageArgs, db *sql.DB) imgDBInfo {
	//Get and check cameraID
	cameraID := getID("digitalCameras", "serial", strconv.Itoa(exif.SerialNumber), db)
	if cameraID == 0 && args.ignoreBlank == false {
		fmt.Println("Camera serial not found in database. Have you added the camera yet?")
		os.Exit(1)
	}
	//Get and check lensID
	lensID := getID("lenses", "serial", exif.LensSerialNumber, db)
	if lensID == 0 && args.ignoreBlank == false {
		fmt.Println("Lens serial not found in database. Have you added the lens yet?")
		os.Exit(1)
	}
	//Check categoryID
	if checkIfExistsInDB("categories", "id", strconv.Itoa(args.categoryID), db) == false && args.ignoreBlank == false {
		fmt.Println("Category ID not found in database. Have you added the category yet?")
		os.Exit(1)
	}
	//Check locationID
	if checkIfExistsInDB("locations", "id", strconv.Itoa(args.locationID), db) == false && args.ignoreBlank == false {
		fmt.Println("Location ID not found in database. Have you added the location yet?")
		os.Exit(1)
	}
	//Assemble IDs into imgDBInfo struct
	dbInfo.cameraID = cameraID
	dbInfo.lensID = lensID
	dbInfo.categoryID = args.categoryID
	dbInfo.locationID = args.locationID

	return dbInfo
}

func outputImg(id int64, outDir string) {
	//Pad ID with zeroes
	idStr := padIDString(strconv.FormatInt(id, 10))
	var src, dst string
	//Copy full image
	src = "/tmp/photosite/img/full.webp"
	dst = outDir + "/full/" + idStr + "f.webp"
	os.Mkdir(outDir+"/full", 0755)
	copyFile(src, dst)
	//Copy thumb image
	src = "/tmp/photosite/img/thumb.webp"
	dst = outDir + "/thumb/" + idStr + "t.webp"
	os.Mkdir(outDir+"/thumb", 0755)
	copyFile(src, dst)
}

func writeToDB(dbInfo imgDBInfo, db *sql.DB) int64 {
	result, err := db.Exec("INSERT INTO digitalPhotos (cameraID, lensID, shutterSpeedNumerator, shutterSpeedDenominator, aperture, iso, focalLength, flash, time, date, locationID, mode, categoryID, oldFilename, extraInfo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
		dbInfo.cameraID, dbInfo.lensID,
		dbInfo.shutterSpeedNumerator, dbInfo.shutterSpeedDenominator,
		dbInfo.aperture, dbInfo.iso,
		dbInfo.focalLength, dbInfo.flash,
		dbInfo.time, dbInfo.date,
		dbInfo.locationID, dbInfo.mode,
		dbInfo.categoryID, dbInfo.oldFilename,
		dbInfo.extraInfo)
	errCheckDB(err)
	id, err := result.LastInsertId()
	errCheckDB(err)
	return id
}

func processArgs(args AddImageArgs, exif ExifData) *AddImageArgs {
	if args.fullResizeX == 0 {
		args.fullResizeX = exif.ImageWidth
	}
	if args.fullResizeY == 0 {
		args.fullResizeY = exif.ImageHeight
	}
	if args.thumbResizeX == 0 {
		args.thumbResizeX = exif.ImageWidth / 3
	}
	if args.thumbResizeY == 0 {
		args.thumbResizeY = exif.ImageHeight / 3
	}
	return &args
}
