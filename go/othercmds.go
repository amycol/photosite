package main

import (
	"database/sql"
	"fmt"
	"os"
	"slices"
)

func addcat(args AddCategoryArgs, db *sql.DB) int64 {
	result, err := db.Exec("INSERT INTO categories (name, description) VALUES (?, ?)",
		args.name, args.description)
	errCheckDB(err)
	id, err := result.LastInsertId()
	errCheckDB(err)
	return id
}

func addloc(args AddLocationArgs, db *sql.DB) int64 {
	result, err := db.Exec("INSERT INTO locations (firstLine, secondLine, city, county, state, country, countryGroup, continent, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
		args.firstLine, args.secondLine,
		args.city, args.county,
		args.state, args.country,
		args.countryGroup, args.continent,
		args.latitude, args.longitude)
	errCheckDB(err)
	id, err := result.LastInsertId()
	errCheckDB(err)
	return id
}

func del(args DeletionArgs, db *sql.DB) {
	//Check if table inputted is valid
	//This helps to protect against SQL injection attacks
	validTables := []string{"digitalPhotos", "digitalCameras", "lenses", "categories", "locations"}
	if slices.Contains(validTables, args.table) != true {
		os.Exit(1)
	}
	//Iterate over every inputted ID
	for i := 0; i < len(args.ids); i++ {
		//Write SQL statement
		query := fmt.Sprintf("DELETE FROM %s WHERE ID = %d",
			args.table, args.ids[i])
		//Execute SQL statement
		_, err := db.Exec(query)
		errCheckDB(err)
	}
	//If photos table, delete image files
	if args.table == "digitalPhotos" {
		//delete file
	}
}
