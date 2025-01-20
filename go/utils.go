package main

import (
	"database/sql"
	"encoding/json"
	"fmt"
	"io"
	"os"
	"os/exec"
	"strconv"
	"strings"
)

// Start & End
func start() *sql.DB {
	dbString := "photosite-go:gAFjxLWY9isu0lAklDTozN@tcp(127.0.0.1:3306)/photosite"
	os.Mkdir("/tmp/photosite", 0755)
	os.Mkdir("/tmp/photosite/img", 0755)
	db := openDB(dbString)
	return db
}

func endOut(id int64, idreturned bool) {
	if id != 0 {
		fmt.Println("Success!")
		fmt.Println(id)
	} else if !idreturned {
		fmt.Println("Operation complete!")
		fmt.Println(-1)
	} else {
		fmt.Println("Unknown failure! ID returned as 0")
		fmt.Println(id)
	}
}

func cleanup(db *sql.DB) {
	db.Close()
	os.RemoveAll("/tmp/photosite/img")
}

// File management
func checkFile(imgPath string) {
	_, err := os.Stat(imgPath)
	errCheckFile(err)
}

func copyFile(src, dst string) {
	var err error
	//Check file exists
	_, err = os.Stat(src)
	errCheckFile(err)
	//Open source file
	var srcFile *os.File
	srcFile, err = os.Open(src)
	errCheckFile(err)
	//Create destination file
	var dstFile *os.File
	dstFile, err = os.Create(dst)
	errCheckFile(err)
	//Copy data from source file to destination file
	_, err = io.Copy(dstFile, srcFile)
	errCheckFile(err)
	//Close files
	srcFile.Close()
	dstFile.Close()
}

// Database functions
func openDB(dbString string) *sql.DB {
	//Open database connection
	db, err := sql.Open("mysql", dbString)
	errCheckDB(err)
	//Check database connection
	err = db.Ping()
	errCheckDB(err)
	return db
}

func getID(table string, column string, data string, db *sql.DB) int {
	var id int
	sqlQuery := fmt.Sprintf("SELECT COALESCE(max(id),0) FROM %s WHERE %s = \"%s\"",
		table, column, data)
	err := db.QueryRow(sqlQuery).Scan(&id)
	errCheckDB(err)
	return id
}

func checkIfExistsInDB(table string, column string, data string, db *sql.DB) bool {
	var result bool
	sqlQuery := fmt.Sprintf("SELECT CASE WHEN EXISTS (SELECT * FROM %s WHERE %s = \"%s\") THEN 'TRUE' ELSE 'FALSE' END",
		table, column, data)
	err := db.QueryRow(sqlQuery).Scan(&result)
	errCheckDB(err)
	return result
}

// External commands
func runCmd(cmd string) {
	_, err := exec.Command("/bin/sh", "-c", cmd).Output()
	errCheckCmd(err)
}

// Conversion & formatting
func padIDString(id string) string {
	for len(id) < 8 {
		id = "0" + id
	}
	return id
}

func getFocalLengthInt(str string) int {
	str, _ = strings.CutSuffix(str, ".0 mm")
	str, _ = strings.CutSuffix(str, " mm")
	fl, err := strconv.Atoi(str)
	if err != nil {
		fmt.Println("Issues processing focal length exif data. Try manual input.")
		fmt.Println(err)
		os.Exit(1)
	}
	return fl
}

func sliceAtoi(in []string) []int {
	var out []int
	for i := 0; i < len(in); i++ {
		j, _ := strconv.Atoi(in[i])
		out = append(out, j)
	}
	return out
}

//EXIF utils

func getExif(imgPath string) ExifData {
	checkFile(imgPath)      //Check image exists
	exifToJson(imgPath)     //Extract EXIF data from image to JSON file
	exif := parseExifJson() //Parse EXIF data JSON file
	return exif
}

func exifToJson(imgPath string) { //reads EXIF data from image and writes to JSON using exiftool
	cmd := fmt.Sprintf("exiftool -json %s > /tmp/photosite/img/exif.json", imgPath)
	runCmd(cmd)
}

func parseExifJson() ExifData {
	exifJson, err := os.ReadFile("/tmp/photosite/img/exif.json")
	errCheckFile(err)

	var exif []ExifData

	err = json.Unmarshal(exifJson, &exif)
	errCheckFile(err)
	//Shutter Speed EXIF data can be either int, float or string so needs processing further
	exif[0].Shutter = strings.Replace(string(exif[0].ExposureTime[:]), "\"", "", 2) //Convert raw JSON byte array to string and remove quotes

	return exif[0]
}

// DBInfo setting
func setValInt(arg, exif int, out *int) {
	if arg != 0 {
		*out = arg
	} else {
		*out = exif
	}
}

func setValStr(arg, exif string, out *string) {
	if arg != "" {
		*out = arg
	} else {
		*out = exif
	}
}

func setValFlt(arg, exif float32, out *float32) {
	if arg != 0 {
		*out = arg
	} else {
		*out = exif
	}
}

// Error handling
func errCheckInput(err error) {
	if err != nil {
		fmt.Println(err)
		os.Exit(1)
	}
}
func errCheckConv(err error) {
	if err != nil {
		fmt.Println(err)
		os.Exit(2)
	}
}
func errCheckCmd(err error) {
	if err != nil {
		fmt.Println(err)
		os.Exit(3)
	}
}
func errCheckDB(err error) {
	if err != nil {
		fmt.Println(err)
		os.Exit(4)
	}
}
func errCheckFile(err error) {
	if err != nil {
		fmt.Println(err)
		os.Exit(5)
	}
}
