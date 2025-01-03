
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


## To Do
Add deletion feature to backend 
Add manual overrides to backend
Complete HTML & PHP for admin panel
Add CSS for admin panel 
Add login system
Add deletion page to admin panel
Add image filtering & sorting to gallery
Add full image viewer page 
Implement automated time description feature on full image viewer and gallery pages
Add settings page to admin panel
Package everything into Docker image