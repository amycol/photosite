package main

import (
	"database/sql"
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
