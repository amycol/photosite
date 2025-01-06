package main

import (
	"os"

	_ "github.com/go-sql-driver/mysql"
)

func main() {
	var id int64
	idreturned := true
	db := start()
	switch os.Args[1] {
	case "addimg":
		args := addimgParseArgs()
		id = addimg(args, db)
	case "addcam":
		args := addcamParseArgs()
		id = addcam(args, db)
	case "addlens":
		args := addlensParseArgs()
		id = addlens(args, db)
	case "addcat":
		args := addcatParseArgs()
		id = addcat(args, db)
	case "addloc":
		args := addlocParseArgs()
		id = addloc(args, db)
	case "del":
		args := delParseArgs()
		del(args, db)
		idreturned = false
	}
	endOut(id, idreturned)
	cleanup(db) //Clean temp files
	println("Operation Complete")
	os.Exit(0)
}
