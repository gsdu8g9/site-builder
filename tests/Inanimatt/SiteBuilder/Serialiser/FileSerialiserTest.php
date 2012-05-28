<?php
namespace Inanimatt\SiteBuilder\Serialiser;

use org\bovigo\vfs\vfsStream;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-05-28 at 10:24:11.
 */
class FileSerialiserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FileSerialiser
     */
    protected $object;
    
    protected $outputPath;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     * 
     * @covers Inanimatt\SiteBuilder\Serialiser\FileSerialiser::__construct
     */
    protected function setUp()
    {
        $this->outputPath = vfsStream::setup('testdir', 0777);
        $this->object = new FileSerialiser(vfsStream::url('testdir'));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }



    /**
     * @covers Inanimatt\SiteBuilder\Serialiser\FileSerialiser::__construct
     */
    public function testConstructor()
    {        
        
        try {
            $object = new FileSerialiser(vfsStream::url('baddir'));
            $this->fail('->__construct() throws a SerialiserException if the path does not exist or cannot be created.');
        } catch(\Exception $e) {
            $this->assertInstanceOf('Inanimatt\SiteBuilder\Exception\SerialiserException', $e, '->__construct() throws a SerialiserException if the path does not exist or cannot be created.');
            $this->assertEquals('Serialiser exception: Failed to create output directory vfs://baddir', $e->getMessage(), '->__construct() throws a Serialiser if the output path does not exist & cannot be created');
        }
    }


    /**
     * @expectedException Inanimatt\SiteBuilder\Exception\SerialiserException
     */
    public function testSerialiserException()
    {
        $object = new FileSerialiser(vfsStream::url('baddir'));
    }
    


    /**
     * @covers Inanimatt\SiteBuilder\Serialiser\FileSerialiser::write
     */
    public function testWrite()
    {
        $this->assertFalse(is_dir(vfsStream::url('exampleDir')), 'Output directory does not exist before write');

        $pathName = vfsStream::url('testdir/testfile.html');

        $file = vfsStream::newFile('testdir/testfile.html', 0666)
                         ->withContent('notoverwritten')
                         ->at($this->outputPath);
        
        $filename = 'testfile.html';
        
        $test_content = 'Lorem ipsum';

        $this->object->write($test_content, $filename);
        
        $this->assertEquals($test_content, $this->outputPath->getChild('testfile.html')->getContent(), 'Can read written content.');
        
        unlink($pathName);
    }
}
