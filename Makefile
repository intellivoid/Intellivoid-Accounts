clean:
	rm -rf build

build:
	mkdir build
	ppm --no-intro --compile="src/IntellivoidAccounts" --directory="build"

install:
	ppm --no-prompt --fix-conflict --install="build/net.intellivoid.accounts.ppm" --branch="production"