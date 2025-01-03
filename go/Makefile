photosite:
	go build -o ./build/photosite

clean:
	rm -r build/*
	go clean 
run:
	go run .

linuxarm64:
	env GOOS=linux GOARCH=arm64 go build -o ./build/photosite-linuxarm64
linuxamd64:
	env GOOS=linux GOARCH=amd64 go build -o ./build/photosite-linuxamd64