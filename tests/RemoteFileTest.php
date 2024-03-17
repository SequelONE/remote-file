<?php
use PHPUnit\Framework\TestCase;
use SequelONE\RemoteFile\RemoteFile;

class RemoteFileTest extends TestCase {
    public function testInvalidUrl() {
        $url = 'invalidUrl';
        $remoteFileSize = RemoteFile::getFileSize($url);
        $this->assertEquals($remoteFileSize, -1);
    }

    public function testDarthPlagueisTheWiseUrlInBytes() {
        $url = 'https://raw.githubusercontent.com/sequelone/RemoteFile/master/darthPlagueisTheWise.txt';
        $remoteFileSize = RemoteFile::getFileSize($url);
        $this->assertEquals($remoteFileSize, 747);
    }

    public function testHumanReadableResultUnder1kB() {
        $url = 'https://raw.githubusercontent.com/sequelone/RemoteFile/master/darthPlagueisTheWise.txt';
        $remoteFileSize = RemoteFile::getHumanReadableFileSize($url);
        $this->assertEquals($remoteFileSize, '747B');
    }

    public function testHumanReadableResultOver1kB() {
        $url = 'https://raw.githubusercontent.com/sequelone/RemoteFile/master/beeMovieScript.txt';
        $remoteFileSize = RemoteFile::getHumanReadableFileSize($url);
        $this->assertEquals($remoteFileSize, '51.52kB');
    }

    public function testHumanReadableResultOver1kBWithPrecision() {
        $url = 'https://raw.githubusercontent.com/sequelone/RemoteFile/master/beeMovieScript.txt';
        $remoteFileSize = RemoteFile::getHumanReadableFileSize($url,4);
        $this->assertEquals($remoteFileSize, '51.5176kB');
    }

    public function testLastModifiedTime(){
        $url = 'http://download.geonames.org/export/dump/readme.txt';
        $carbonLastModified = RemoteFile::getLastModified($url);
        $this->assertInstanceOf(\Carbon\Carbon::class, $carbonLastModified);
    }

    /**
     * I use google.com because they are not returning a Last-Modified header.
     *
     * @throws \SequelONE\RemoteFile\Exceptions\GetHeadersError
     * @throws \SequelONE\RemoteFile\Exceptions\MissingLastModifiedHeader
     */
    public function testMissingLastModifiedShouldThrowException() {
        $this->expectException( \SequelONE\RemoteFile\Exceptions\MissingLastModifiedHeader::class );
        $url = 'http://google.com';
        RemoteFile::getLastModified( $url );
    }


    /**
     * @throws \SequelONE\RemoteFile\Exceptions\GetHeadersError
     * @throws \SequelONE\RemoteFile\Exceptions\MissingLastModifiedHeader
     */
    public function testLastModifiedShouldThrowExceptionWhenRequestingNonExistentUrl() {
        $this->expectException( \SequelONE\RemoteFile\Exceptions\GetHeadersError::class );
        $url = 'http://aklsfghdfagsie7yrlshdgirygafwesygfksydgkfahzbdskygfkzxdygkfhzbsdkfaksdfgkadrennen.com';
        RemoteFile::getLastModified( $url );
    }


}