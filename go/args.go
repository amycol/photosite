package main

import (
	"flag"
	"os"
)

func addimgParseArgs() AddImageArgs {
	addimgCmd := flag.NewFlagSet("addimg", flag.ExitOnError)
	args := AddImageArgs{}

	addimgCmd.StringVar(&args.imgPath, "img", "./img.png", "Input image path")
	addimgCmd.StringVar(&args.outDir, "outdir", "./out", "Output directory path")
	addimgCmd.BoolVar(&args.removeInputImgAtCompletion, "rm", false, "Remove input image on completion")
	addimgCmd.IntVar(&args.fullResizeX, "fullresizex", 0, "Resolution X to resize fullscreen images to when converting to webp. Set as 0 to use input image resolution.")
	addimgCmd.IntVar(&args.fullResizeY, "fullresizey", 0, "Resolution Y to resize fullscreen images to when converting to webp. Set as 0 to use input image resolution.")
	addimgCmd.IntVar(&args.thumbResizeX, "thumbresizex", 0, "Resolution X to resize thumbnail images to when converting to webp. Set as 0 to use input image resolution / 3.")
	addimgCmd.IntVar(&args.thumbResizeY, "thumbresizey", 0, "Resolution Y to resize thumbnail images to when converting to webp. Set as 0 to use input image resolution / 3.")
	addimgCmd.IntVar(&args.fullQuality, "fullquality", 80, "Compression quality for fullscreen images when converting to webp")
	addimgCmd.IntVar(&args.thumbQuality, "thumbquality", 80, "Compression quality for fullscreen images when converting to webp")
	addimgCmd.StringVar(&args.fullLandscapeWMPath, "fulllandwmpath", "./watermarks/landscape.png", "Landscape fullscreen watermark path")
	addimgCmd.StringVar(&args.fullPortraitWMPath, "fullportwmpath", "./watermarks/portrait.png", "Portrait fullscreen watermark path")
	addimgCmd.IntVar(&args.fullWMOpacity, "fullwmopacity", 25, "Watermark fullscreen opacity")
	addimgCmd.StringVar(&args.thumbLandscapeWMPath, "thumblandwmpath", "./watermarks/landscape.png", "Landscape thumbnail watermark path")
	addimgCmd.StringVar(&args.thumbPortraitWMPath, "thumbportwmpath", "./watermarks/portrait.png", "Portrait thumbnail watermark path")
	addimgCmd.IntVar(&args.thumbWMOpacity, "thumbwmopacity", 25, "Watermark thumbnail opacity")
	addimgCmd.BoolVar(&args.ignoreBlank, "ignoreblank", false, "Ignore blank fields before uploading image to database")
	addimgCmd.IntVar(&args.categoryID, "catid", 0, "CategoryID")
	addimgCmd.IntVar(&args.locationID, "locid", 0, "LocationID")
	addimgCmd.StringVar(&args.extraInfo, "extrainfo", "", "Extra info about the image")

	addimgCmd.Parse(os.Args[2:])
	return args
}

func addcamParseArgs() AddCameraArgs {
	addcamCmd := flag.NewFlagSet("addcam", flag.ExitOnError)
	args := AddCameraArgs{}

	addcamCmd.StringVar(&args.imgPath, "img", "./img.png", "Input image path")
	addcamCmd.IntVar(&args.serial, "serial", 0, "Camera serial number")
	addcamCmd.StringVar(&args.manufacturer, "manufacturer", "", "Camera manufacturer")
	addcamCmd.StringVar(&args.model, "model", "", "Camera model")
	addcamCmd.IntVar(&args.imageWidth, "width", 0, "Camera image width (in pixels)")
	addcamCmd.IntVar(&args.imageHeight, "height", 0, "Camera image height (in pixels)")

	addcamCmd.Parse(os.Args[2:])

	return args
}

func addlensParseArgs() AddLensArgs {
	addlensCmd := flag.NewFlagSet("addlens", flag.ExitOnError)
	args := AddLensArgs{}

	addlensCmd.StringVar(&args.imgPath, "img", "./img.png", "Input image path")
	addlensCmd.StringVar(&args.manufacturer, "manufacturer", "", "Lens manufacturer")
	addlensCmd.StringVar(&args.mount, "mount", "", "Lens mount")
	addlensCmd.IntVar(&args.blades, "blades", 0, "Lens aperture blade count, defaults to 0")
	addlensCmd.BoolVar(&args.autofocus, "autofocus", true, "Lens autofocus (Boolean, defaults to true)")

	addlensCmd.Parse(os.Args[2:])
	return args
}

func addcatParseArgs() AddCategoryArgs {
	addcatCmd := flag.NewFlagSet("addcat", flag.ExitOnError)
	args := AddCategoryArgs{}

	addcatCmd.StringVar(&args.name, "name", "", "Category name")
	addcatCmd.StringVar(&args.description, "desc", "", "Category description")

	addcatCmd.Parse(os.Args[2:])
	return args
}

func addlocParseArgs() AddLocationArgs {
	addlocCmd := flag.NewFlagSet("addloc", flag.ExitOnError)
	args := AddLocationArgs{}

	addlocCmd.StringVar(&args.firstLine, "firstline", "", "First line")
	addlocCmd.StringVar(&args.secondLine, "secondline", "", "Second line")
	addlocCmd.StringVar(&args.city, "city", "", "City")
	addlocCmd.StringVar(&args.county, "county", "", "County")
	addlocCmd.StringVar(&args.state, "state", "", "State")
	addlocCmd.StringVar(&args.country, "country", "", "Country")
	addlocCmd.StringVar(&args.countryGroup, "countrygroup", "", "Country Group (e.g. United Kingdom)")
	addlocCmd.StringVar(&args.continent, "continent", "", "Continent")
	addlocCmd.Float64Var(&args.latitude, "latitude", 0, "Latitude")
	addlocCmd.Float64Var(&args.longitude, "longitude", 0, "Longitude")

	addlocCmd.Parse(os.Args[2:])
	return args
}
