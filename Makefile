main:
	make -C go linuxarm64
	mkdir -p docker/binaries
	cp go/build/photosite-linuxarm64 docker/binaries/photosite
	make -C docker 