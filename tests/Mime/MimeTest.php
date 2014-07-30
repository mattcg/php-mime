<?php

/**
 * LICENSE: This source code is subject to the license that is available
 * in the LICENSE file distributed along with this package.
 *
 * @package    Mime
 * @author     Matthew Caruana Galizia <mcg@karwana.com>
 * @copyright  Karwana Ltd
 * @since      File available since Release 1.0.0
 */

namespace Karwana\Mime;

class MimeTest extends \PHPUnit_Framework_TestCase {

	private function getPath($file_name) {
		return implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'data', $file_name));
	}

	public function testGetMagicType_GuessesType() {
		$mime_type = Mime::getMagicType($this->getPath('test.jpg'));
		$this->assertEquals('image/jpeg', $mime_type);
	}

	public function testGetMagicType_ReturnsNullForIndterminateType() {
		$file_path = $this->getPath('test');

		$file_info = finfo_open(FILEINFO_MIME_TYPE);
		$mime_type = finfo_file($file_info, $file_path);
		finfo_close($file_info);

		// finfo_file would ordinarily return an invalid type.
		$this->assertEquals('inode/x-empty', $mime_type);

		$mime_type = Mime::getMagicType($file_path);
		$this->assertNull($mime_type);
	}

	public function testGetExtensionForType_ReturnsNullForUnknownType() {
		$this->assertNull(Mime::getExtensionForType('blabla'));
	}

	public function testGetExtensionForType_ReturnsFirstExtension() {

		// Check for type with multiple extensions.
		$this->assertEquals('jpeg', Mime::getExtensionForType('image/jpeg'));

		// Check for type with single extension.
		$this->assertEquals('pdf', Mime::getExtensionForType('application/pdf'));
	}

	public function testGuessExtension_ReturnsExtensionPresentInFileName() {
		$extension = Mime::guessExtension('does_not_exist.jpg');
		$this->assertEquals('jpg', $extension);
	}

	public function testGuessExtension_ReturnsExtensionPresentInReferenceFileName() {
		$extension = Mime::guessExtension('does_not_exist', 'also_does_not_exist.jpg');
		$this->assertEquals('jpg', $extension);
	}

	public function testGuessExtension_UsesMagic() {
		$extension = Mime::guessExtension($this->getPath('test_jpg'));
		$this->assertEquals('jpeg', $extension);
	}

	public function testGuessExtension_ReturnsDefault() {
		$extension = Mime::guessExtension($this->getPath('test'), null, 'test_extension');
		$this->assertEquals('test_extension', $extension);
	}

	public function testGuessExtension_IgnoresInvalidExtension() {
		$extension = Mime::guessExtension($this->getPath('test_jpg.you_lie'));
		$this->assertEquals('jpeg', $extension);
	}

	public function testGetTypeForExtension_ReturnsFirstExtension() {

		// Check for type with multiple extensions.
		$this->assertEquals('image/jpeg', Mime::getTypeForExtension('jpg'));
		$this->assertEquals('image/jpeg', Mime::getTypeForExtension('jpeg'));

		// Check for type with single extension.
		$this->assertEquals('application/pdf', Mime::getTypeForExtension('pdf'));
	}

	public function testGetTypeForExtension_ReturnsNullForUnknownExtension() {
		$this->assertNull(Mime::getTypeForExtension('blabla'));
	}

	public function testGuessType_UsesExtensionPresentInFileName() {
		$mime_type = Mime::guessType('does_not_exist.jpg');
		$this->assertEquals('image/jpeg', $mime_type);
	}

	public function testGuessType_UsesExtensionPresentInReferenceFileName() {
		$mime_type = Mime::guessType('does_not_exist', 'also_does_not_exist.jpg');
		$this->assertEquals('image/jpeg', $mime_type);
	}

	public function testGuessType_UsesMagic() {
		$mime_type = Mime::guessType($this->getPath('test_jpg'));
		$this->assertEquals('image/jpeg', $mime_type);
	}

	public function testGuessType_ReturnsDefault() {
		$mime_type = Mime::guessType($this->getPath('test'), null, 'test/type');
		$this->assertEquals('test/type', $mime_type);
	}

	public function testHasType() {
		$this->assertTrue(Mime::hasType('image/jpeg'));
		$this->assertFalse(Mime::hasType('test/type'));
	}

	public function testGetExtensionsForType_ReturnsArray() {
		$this->assertEquals(array('jpeg', 'jpg', 'jpe'), Mime::getExtensionsForType('image/jpeg'));
		$this->assertEquals(array('pdf'), Mime::getExtensionsForType('application/pdf'));
	}

	public function testGetExtensionsForType_ReturnsNullForUnknownType() {
		$this->assertNull(Mime::getExtensionsForType('blabla'));
	}

	public function testHasExtension() {
		$this->assertTrue(Mime::hasExtension('jpeg'));
		$this->assertTrue(Mime::hasExtension('jpg'));
		$this->assertTrue(Mime::hasExtension('pdf'));
		$this->assertFalse(Mime::hasExtension('blabla'));
	}
}
