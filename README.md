# IMPORTANT NOTE:
### This project is currently very unfinished. It likely will not work at all. 
# photosite
### A mostly automated photography website solution, features including optimising disk usage & bandwidth, automated time description and semi-automated image tagging. Designed with compatibility, speed and efficiency prioritised. 
## Build & Installation
### Retrieving the project
Ensure `git` is installed, and run:
`git clone https://github.com/amycol/photosite`
### Building the Go backend
Ensure `go` is installed.
`cd` into the Go directory and run `make`:
```shell
cd  photosite/go
make
```
Binary will be located at `./photosite/go/build/photosite` on Linux/Unix/MacOS. Untested on Windows but will likely be located at `./photosite/go/build/photosite.exe`


ONLY WORKS WITH JPEG
    PNG untested
    TIFF either mutes or oversaturates colours, and messes with contrast
    JPEG seems to work fine
    This is an issue with CWEBP

## To Do
# Backend
Add manual overrides to backend -- x 
Add comments to SQL columns to indicate optional overrides x
# Admin Panel
Add colours to represent optional overrides x
Add login system ? x
Add settings page to admin panel ? x
# Public Site













# PreProcessor //
Literally everything
# PSUP Files //
Renamed ZIP (Like JAR)
Contains data to verify where the pre-processor came from
imgs.json contains array of all images and their original names
Structure
    pack.psup
        info.json
        imgs.json
        img
            full
                0001.webp
            thumb
                0001.webp
        exif
            0001.json
        

# Bugs


 
command line admin client would be nice but probably wont have time