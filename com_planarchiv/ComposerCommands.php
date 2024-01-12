<?php

namespace Bakual\Planarchiv;

class ComposerCommands
{
	public static function cleanup()
	{
		$files = array(
			'behat/transliterator/CHANGELOG.md',
			'behat/transliterator/CONTRIBUTING.md',
			'behat/transliterator/README.md',
			'jeroendesloovere/vcard/.gitignore',
			'jeroendesloovere/vcard/.travis.yml',
			'jeroendesloovere/vcard/CHANGELOG.md',
			'jeroendesloovere/vcard/phpunit.xml.dist',
			'jeroendesloovere/vcard/README.md',
		);

		foreach ($files as $file)
		{
			$file = __DIR__ . '/admin/vendor/' . $file;

			if (file_exists($file))
			{
				unlink($file);
				echo "File deleted! ({$file})\n";
			}
		}
	}

	private static function deleteDirectory($dir)
	{
		$fullDir = __DIR__ . '/admin/vendor/' . $dir;

		$dir_handle = is_dir($fullDir) ? opendir($fullDir) : false;

		// Falls Verzeichnis nicht geoeffnet werden kann, mit Fehlermeldung terminieren
		if (!$dir_handle)
		{
			echo "Can't find {$dir}\n";

			return false;
		}

		while ($file = readdir($dir_handle))
		{
			if ($file != "." && $file != "..")
			{
				if (!is_dir($fullDir . "/" . $file))
				{
					//Datei loeschen
					@unlink($fullDir . "/" . $file);
				}
				else
				{
					//Falls es sich um ein Verzeichnis handelt, "deleteDirectory" aufrufen
					self::deleteDirectory($dir . '/' . $file);
				}
			}
		}

		closedir($dir_handle);

		//Verzeichnis löschen
		rmdir($fullDir);

		echo "Directory deleted! ({$dir})\n";

		return true;
	}
}
