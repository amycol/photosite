package main

import (
	"fmt"
	//"strings"
)

func processImage(args AddImageArgs, exif ExifData) {
	watermarkFull(args, exif)
	watermarkThumb(args, exif)
	createFullWebp(args)
	createThumbWebp(args)
}

func createThumbWebp(args AddImageArgs) {
	//Writing shell command to convert to WebP using cwebp
	cmd := fmt.Sprintf("cwebp -mt -resize %d %d -q %d %s -o /tmp/photosite/img/thumb.webp",
		args.thumbResizeX, args.thumbResizeY,
		args.thumbQuality, "/tmp/photosite/img/thumb-wm.png")
	//Executing the shell command
	fmt.Println("Converting thumb image to webp...")
	runCmd(cmd)
}
func createFullWebp(args AddImageArgs) {
	//Writing shell command to convert to WebP using cwebp
	cmd := fmt.Sprintf("cwebp -mt -resize %d %d -q %d %s -o /tmp/photosite/img/full.webp",
		args.fullResizeX, args.fullResizeY,
		args.fullQuality, "/tmp/photosite/img/full-wm.png")
	//Executing the shell command
	fmt.Println("Converting full image to webp...")
	runCmd(cmd)
}

func watermarkFull(args AddImageArgs, exif ExifData) {
	//Select correct watermark for whether image is landscape or portrait
	wm := selectWM(args, exif, "full")
	//Using imagemagick CLI to overlay watermark
	cmd := fmt.Sprintf("magick composite -watermark %d %s %s /tmp/photosite/img/full-wm.png",
		args.fullWMOpacity,
		wm, args.imgPath)
	//Executing the shell command
	fmt.Println("Adding watermark to full image...")
	runCmd(cmd)
}

func watermarkThumb(args AddImageArgs, exif ExifData) {
	//Select correct watermark for whether image is landscape or portrait
	wm := selectWM(args, exif, "thumb")
	//Using imagemagick CLI to overlay watermark
	cmd := fmt.Sprintf("magick composite -watermark %d %s %s /tmp/photosite/img/thumb-wm.png",
		args.thumbWMOpacity,
		wm, args.imgPath)
	//Executing the shell command
	fmt.Println("Adding watermark to thumb image...")
	runCmd(cmd)
}

func selectWM(args AddImageArgs, exif ExifData, imgType string) string {
	var wm string
	if imgType == "full" {
		if exif.ImageWidth > exif.ImageHeight {
			wm = args.fullLandscapeWMPath
		} else {
			wm = args.fullPortraitWMPath
		}
	} else {
		if exif.ImageWidth > exif.ImageHeight {
			wm = args.thumbLandscapeWMPath
		} else {
			wm = args.thumbPortraitWMPath
		}
	}
	return wm
}
