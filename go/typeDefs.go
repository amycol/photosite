package main

import "encoding/json"

type AddImageArgs struct {
	imgPath                    string
	outDir                     string
	removeInputImgAtCompletion bool
	fullResizeX                int
	fullResizeY                int
	thumbResizeX               int
	thumbResizeY               int
	fullQuality                int
	thumbQuality               int
	fullLandscapeWMPath        string
	fullPortraitWMPath         string
	fullWMOpacity              int
	thumbLandscapeWMPath       string
	thumbPortraitWMPath        string
	thumbWMOpacity             int
	ignoreBlank                bool
	categoryID                 int
	locationID                 int
	extraInfo                  string
}
type AddCameraArgs struct {
	imgPath                    string
	removeInputImgAtCompletion bool
	serial                     int
	manufacturer               string
	model                      string
	imageWidth                 int
	imageHeight                int
}
type AddLensArgs struct {
	imgPath                    string
	removeInputImgAtCompletion bool
	serial                     string
	manufacturer               string
	name                       string
	prime                      bool
	minFocalLength             int
	maxFocalLength             int
	minAperture                float32
	maxAperture                float32
	mount                      string
	blades                     int
	autofocus                  bool
}
type AddCategoryArgs struct {
	name        string
	description string
}
type AddLocationArgs struct {
	firstLine    string
	secondLine   string
	city         string
	county       string
	state        string
	country      string
	countryGroup string
	continent    string
	latitude     float64
	longitude    float64
	timezone     string
}
type DeletionArgs struct {
	table string
	ids   []int
}
type ExifData struct {
	FileName         string
	ImageWidth       int
	ImageHeight      int
	DateTimeOriginal string
	Shutter          string
	ExposureTime     json.RawMessage //Shutter speed - needs to be json.RawMessage (byte array) as it can be multiple types
	FNumber          float32
	ISO              int
	FocalLength      string
	Flash            string
	ExposureMode     string
	SerialNumber     int
	Make             string
	Model            string
	LensSerialNumber string
	LensType         string
	LensID           string
	MaxFocalLength   string
	MinFocalLength   string
	MaxAperture      float32
	MinAperture      float32
}
type imgDBInfo struct {
	cameraID                int
	lensID                  int
	shutterSpeedNumerator   float32
	shutterSpeedDenominator float32
	aperture                float32
	iso                     int
	focalLength             int
	flash                   bool
	time                    string //Format: hh:mm:ss
	date                    string //Format: yyyy-mm-dd
	locationID              int
	mode                    string
	categoryID              int
	oldFilename             string
	extraInfo               string
}
type camDBInfo struct {
	serial       int
	manufacturer string
	model        string
	imageWidth   int
	imageHeight  int
}
type lensDBInfo struct {
	serial         string
	manufacturer   string
	name           string
	prime          bool
	minFocalLength int
	maxFocalLength int
	minAperture    float32
	maxAperture    float32
	mount          string
	blades         int
	autofocus      bool
}
