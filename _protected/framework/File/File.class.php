<?php
/**
 * @title            File Class
 * @desc             Useful methods for handling files.
 *
 * @author           Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright        (c) 2012-2013, Pierre-Henry Soria. All Rights Reserved.
 * @license          GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package          PH7 / Framework / File
 * @version          1.3
 */

namespace PH7\Framework\File;
defined('PH7') or exit('Restricted access');

class File
{

    // End Of Line relative to the operating system
    const EOL = PHP_EOL;

    /**
     * Mime Types list.
     *
     * @access private
     * @staticvar array $_aMimeTypes
     */
    private static $_aMimeTypes = [
        'pdf' => 'application/pdf',
        'txt' => 'text/plain',
        'html' => 'text/html',
        'htm' => 'text/html',
        'exe' => 'application/octet-stream',
        'zip' => 'application/zip',
        'doc' => 'application/msword',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'gif' => 'image/gif',
        'png' => 'image/png',
        'jpeg' => 'image/jpg',
        'jpg' => 'image/jpg',
        'ico' => 'image/x-icon',
        'eot' => 'application/vnd.ms-fontobject',
        'otf' => 'application/octet-stream',
        'ttf' => 'application/octet-stream',
        'woff' => 'application/octet-stream',
        'svg' => 'application/octet-stream',
        'swf' => 'application/x-shockwave-flash',
        'mp3' => 'audio/mpeg',
        'mp4' => 'video/mp4',
        'mov' => 'video/quicktime',
        'avi' => 'video/x-msvideo',
        'php' => 'text/plain',
    ];

    /**
     * @param string $sExt Extension File.
     * @return string (string | null) Returns the "mime type" if it is found, otherwise "null"
     */
    public function getMimeType($sExt)
    {
        return (array_key_exists($sExt, static::$_aMimeTypes)) ? static::$_aMimeTypes[$sExt] : null;
    }

    /**
     * Get Extension file without the dot.
     *
     * @param string $sFile The File Name.
     * @return string
     */
    public function getFileExt($sFile)
    {
        return strtolower(substr(strrchr($sFile, PH7_DOT), 1));
    }

    /**
     * Get File without Extension and the dot.
     * This function is smarter than just a code like this, substr($sFile,0,strpos($sFile,'.'))
     * Just look at the example below for you to realize that the function removes only the extension and nothing else!
     * Example 1 "my_file.pl" The return value is "my_file"
     * Example 2 "my_file.inc.pl" The return value is "my_file.inc"
     * Example 3 "my_file.class.html.php" The return value is "my_file.class.html"
     *
     * @see \PH7\Framework\File\File::getFileExt() To see the method that retrieves the file extension.
     * @param string $sFile
     * @return string
     */
    public function getFileWithoutExt($sFile)
    {
        $sExt = $this->getFileExt($sFile);
        return str_replace(PH7_DOT . $sExt, '', $sFile);
    }

    /**
     * Check if file exists.
     *
     * @param mixed (array | string) $mFile
     * @return boolean TRUE if file exists, FALSE otherwise.
     */
    public function existsFile($mFile)
    {
        $bExists = false; // Default value

        if (is_array($mFile))
        {
            foreach ($mFile as $sF)
            {
                if (!$bExists = $this->existsFile($sF))
                    break;
            }
        }
        else
        {
            $bExists = is_file($mFile);
        }

        return $bExists;
    }

    /**
     * Check if directory exists.
     *
     * @param mixed (array | string) $mDir
     * @return boolean TRUE if file exists, FALSE otherwise.
     */
    public function existsDir($mDir)
    {
        $bExists = false; // Default value

        if (is_array($mDir))
        {
            foreach ($mDir as $sD)
            {
                if (!$bExists = $this->existsDir($sD))
                    break;
            }
        }
        else
        {
            $bExists = is_dir($mDir);
        }

        return $bExists;
    }

    /**
     * @param string $sDir The directory.
     * @return array The list of the folder that is in the directory.
     */
    public function getDirList($sDir)
    {
        $aDirList = array();

        if ($rHandle = opendir($sDir))
        {
            while (false !== ($sFile = readdir($rHandle)))
            {
                if ($sFile != '.' && $sFile != '..' && is_dir($sDir . PH7_DS . $sFile))
                    $aDirList[] = $sFile;
            }
            asort($aDirList);
            reset($aDirList);
        }
        closedir($rHandle);
        return $aDirList;
    }

    /**
     * Gets file size.
     *
     * @param string $sFile
     * @return integer The size of the file in bytes.
     */
    public function size($sFile)
    {
        return filesize($sFile);
    }

    /**
     * @param string $sDir
     * @param string $sExt Optional, retrieves only files with specific extensions. Default value is NULL.
     * @return array List of files sorted alphabetically.
     */
    public function getFileList($sDir, $sExt = null)
    {
        $aTree = array();
        $sDir = $this->checkExtDir($sDir);

        if (is_dir($sDir) && $rHandle = opendir($sDir))
        {
            while (false !== ($sF = readdir($rHandle)))
            {
                if ($sF != '.' && $sF != '..')
                {
                    if (is_dir($sDir . $sF))
                    {
                        $aTree = array_merge($aTree, $this->getFileList($sDir . $sF));
                    }
                    else
                    {
                        if (!empty($sExt))
                        {
                            if (substr($sF, -strlen($sExt)) === $sExt)
                                $aTree[] = $sDir . $sF;
                        }
                        else
                        {
                            $aTree[] = $sDir . $sF;
                        }
                    }
                }
            }
            sort($aTree);
        }
        closedir($rHandle);
        return $aTree;
    }

    /**
     * Make sure that folder names have a trailing.
     *
     * @param string $sDir The directory.
     * @param bool $bStart for check extension directory start. Default FALSE
     * @param bool $bEnd for check extension end. Default TRUE
     * @return string $sDir Directory
     */
    public function checkExtDir($sDir, $bStart = false, $bEnd = true)
    {
        if ($bStart === true && substr($sDir, 0, 1) !== '/')
            $sDir = '/' . $sDir;

        if ($bEnd === true && substr($sDir, -1) !== '/')
            $sDir .= '/';

        return $sDir;
    }

    /**
     * Creates a directory if they are in an array. If it does not exist and
     * allows the creation of nested directories specified in the pathname.
     *
     * @param mixed (string | array) $mDir
     * @param integer (octal) $iMode Default: 0777
     * @return void
     * @throws \PH7\Framework\File\Exception If the file cannot be created.
     */
    public function createDir($mDir, $iMode = 0777)
    {
        if (is_array($mDir))
        {
            foreach ($mDir as $sD) $this->createDir($sD);
        }
        else
        {
            if (!is_dir($mDir))
                if (!@mkdir($mDir, $iMode, true))
                    throw new Exception('Error to create file: \'' . $mDir . '\'<br /> Please verify that the directory permission is in writing mode.');
        }
    }

    /**
     * Copy the contents of a directory into another and
     * checks if the file or directory exists with file_exists() function since it checks the existance of a file or directory (because, as in the Unix OS, a directory is a file).
     *
     * @param string $sFrom File or directory
     * @param string $sTo File or directory
     * @return boolean Returns true if everything went well except if the file / directory from does not exist or if the copy went wrong.
     */
    public function copy($sFrom, $sTo)
    {
        if (!file_exists($sFrom)) return false;

        $oIterator = new \RecursiveIteratorIterator($this->getDirectoryIterator($sFrom), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($oIterator as $sFromFile)
        {
            if ($sFromFile->isFile())
            {
                $sToFile = $sTo . substr($sFromFile, strlen($sFrom));
                $this->createDir(dirname($sToFile));
                return copy($sFrom, $sToFile);
            }
        }
    }

    /**
     * Copy a file or directory with the Unix cp command and
     * checks if the file or directory exists with file_exists() function since it checks the existance of a file or directory (because, as in the Unix OS, a directory is a file).
     *
     * @param string $sFrom File or directory
     * @param string $sTo File or directory
     * @return void
     */
    public function copyMost($sFrom, $sTo)
    {
        if (file_exists($sFrom))
            system("cp -r $sFrom $sTo");
    }

    /**
     * Renames the contents of a directory into another and
     * checks if the file or directory exists with file_exists() function since it checks the existance of a file or directory (because, as in the Unix OS, a directory is a file).
     *
     * @param string $sFrom File or directory
     * @param string $sTo File or directory
     * @return boolean Returns true if everything went well except if the file / directory from does not exist or if the copy went wrong.
     */
    public function rename($sFrom, $sTo)
    {
        if (!file_exists($sFrom)) return false;

        $oIterator = new \RecursiveIteratorIterator($this->getDirectoryIterator($sFrom), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($oIterator as $sFromFile)
        {
            if ($sFromFile->isFile())
            {
                $sToFile = $sTo . substr($sFromFile, strlen($sFrom));
                $this->createDir(dirname($sToFile));
                return rename($sFrom, $sToFile);
            }
        }
    }

    /**
     * Renames a file or directory with the Unix mv command and
     * checks if the file or directory exists with file_exists() function since it checks the existance of a file or directory (because, as in the Unix OS, a directory is a file).
     *
     * @param string $sFrom File or directory.
     * @param string $sTo File or directory.
     * @return void
     */
    public function renameMost($sFrom, $sTo)
    {
        if (file_exists($sFrom))
            system("mv $sFrom $sTo");
    }

    /**
     * Deletes a file or files if they are in an array.
     * If the file does not exist, the function does nothing.
     *
     * @param mixed (string | array) $mFile
     * @return void
     */
    public function deleteFile($mFile)
    {
        if (is_array($mFile))
            foreach ($mFile as $sF) $this->deleteFile($sF);
        else
            if (is_file($mFile)) unlink($mFile);
    }

    /**
     * For deleting Directory and files!
     * A "rmdir" function improved PHP which also delete files in a directory.
     *
     * @param string $sPath The path
     * @return boolean
     */
    public function deleteDir($sPath)
    {
        return is_file($sPath) ? unlink($sPath) : is_dir($sPath) ? array_map(array($this, 'deleteDir'), glob($sPath . '/*')) === @rmdir($sPath) : false;
    }

    /**
     * Remove the contents of a directory.
     *
     * @param string $sDir
     * @return void
     */
    public function remove($sDir)
    {
        $oIterator = new \RecursiveIteratorIterator($this->getDirectoryIterator($sDir), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($oIterator as $sPath) ($sPath->isFile()) ? unlink($sPath) : @rmdir($sPath);
        @rmdir($sDir);
    }

    /**
     * @param string File Name.
     * @return mixed (integer | boolean) Returns the "time the file was last modified", or "false" if it not found.
     */
    public function modificationTime($sFile)
    {
        return (is_file($sFile)) ? filemtime($sFile) : false;
    }

    /**
     * Delay script execution.
     *
     * @param integer $iSleep Halt time in seconds. Optional parameter, default value is 5.
     * @return mixed (integer | boolean) Returns "0" on success, or "false" on error.
     */
    public function sleep($iSleep = null)
    {
        $iSleep = (!empty($iSleep)) ? $iSleep : 5;
        return sleep($iSleep);
    }

    /**
     * Changes permission on a file or directory.
     *
     * @param string $sFile
     * @param integer $iMode Octal Permission for the file.
     * @return boolean
     */
    public function chmod($sFile, $iMode)
    {
        // file_exists function verify the existence of a "file" or "folder"!
        if (file_exists($sFile) && $this->getOctalAccess($sFile) !== $iMode)
            return @chmod($sFile, $iMode);

        return false;
    }

    /**
     * @param string $sFile
     * @return string Octal Permissions.
     */
    public function getOctalAccess($sFile)
    {
        return substr(sprintf('%o', fileperms($sFile)), -4);
    }

    /**
     * @param string $sData
     * @return string
     */
    public function pack($sData)
    {
        return urlencode(serialize($sData));
    }

    /**
     * Get the size of a directory.
     *
     * @param string $sPath
     * @return integer The size of the file in bytes.
     */
    public function getDirSize($sPath)
    {
        if (!is_dir($sPath)) return 0;
        if (!($rHandle = opendir($sPath))) return 0;

        $iSize = 0;
        while (false !== ($sFile = readdir($rHandle)))
        {
            if ($sFile != '.' && $sFile != '..')
            {
                $sFullPath = $sPath . PH7_DS . $sFile;

                if (is_dir($sFullPath))
                    $iSize = $this->getDirSize($sFullPath);
                else
                    $iSize += $this->size($sFullPath);
            }
        }
        closedir($rHandle);
        return $iSize;
    }

    /**
     * Get free space of a directory.
     *
     * @param string $sPath
     * @return float The number of available bytes as a float.
     */
    public function getDirFreeSpace($sPath)
    {
        return disk_free_space($sPath);
    }

    /**
     * @param string $sData
     * @return mixed (boolean, integer, float, string, array or object)
     */
    public function unpack($sData)
    {
        return unserialize(urldecode($sData));
    }

    /**
     * For download file.
     *
     * @param string $sFile file in download.
     * @param string $sName if file in download.
     * @param string $sMimeType Optional, default value is NULL.
     * @return void
     */
    public function download($sFile, $sName, $sMimeType = null)
    {
        /*
          This function takes a path to a file to output ($sFile),
          the filename that the browser will see ($sName) and
          the MIME type of the file ($sMimeType, optional).

          If you want to do something on download abort/finish,
          register_shutdown_function('function_name');
         */

        //if (!is_readable($sFile)) exit('File not found or inaccessible!');

        $sName = \PH7\Framework\Url\Url::decode($sName); // Clean the name file

        /* Figure out the MIME type (if not specified) */


        if (empty($sMimeType))
        {
            $sFileExtension = $this->getFileExt($sFile);

            $mGetMimeType = $this->getMimeType($sFileExtension);

            if (!empty($mGetMimeType))
                $sMimeType = $mGetMimeType;
            else
                $sMimeType = 'application/force-download';
        }

        @ob_end_clean(); // Turn off output buffering to decrease CPU usage

        (new \PH7\Framework\Navigation\Browser)->nocache(); // No cache

        $sPrefix = \PH7\Framework\Registry\Registry::getInstance()->site_name . '_'; // the prefix
        header('Content-Type: ' . $sMimeType);
        header('Content-Disposition: attachment; filename=' . \PH7\Framework\Parse\Url::clean($sPrefix) . $sName);
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        header('Content-Length: ' . $this->size($sFile));
        readfile($sFile);
    }

    /**
     * Write Header Contents.
     *
     * @param string $sHeader Text to be shown in the headers
     * @param array $aFile
     * @return void
     */
    public function writeHeader($sHeader, $aFile = array())
    {
        for ($i = 0, $iCountFiles = count($aFile); $i < $iCountFiles; $i++)
        {
            $rHandle = fopen($aFile[$i], 'wb+');
            $sData = '';
            if ($this->size($aFile[$i]) > 0)
            {
                $aData = fread($rHandle, $this->size($aFile[$i]));
                fwrite($rHandle, $sHeader . static::EOL . $sData);
            }
            fclose($rHandle);
        }
    }

    /**
     * Writes and saves the contents to a file.
     * It also creates a temporary file does not delete the original file if something goes wrong during the recording file.
     *
     * @param string $sFile
     * @param string $sData
     * @return integer Returns the number of bytes written, or NULL on error.
     */
    public function save($sFile, $sData)
    {
        $sTmpFile = $this->getFileWithoutExt($sFile) . '.tmp.' . $this->getFileExt($sFile);
        $iWritten = (new \SplFileObject($sTmpFile, 'wb'))->fwrite($sData);

        if ($iWritten != null) {
            // Copy of the temporary file to the original file if no problem occurred.
            copy($sTmpFile, $sFile);
        }

        // Deletes the temporary file.
        $this->deleteFile($sTmpFile);

        return $iWritten;
    }

    /**
     * Reading Files.
     *
     * @param string $sPath
     * @param mixed (array | string) $mFiles
     * @return mixed (array | string) The Files.
     */
    public function readFiles($sPath = './', &$mFiles)
    {
        if (!($rHandle = opendir($sPath))) return false;

        while (false !== ($sFile = readdir($rHandle)))
        {
            if ($sFile != '.' && $sFile != '..')
            {
                if (strpos($sFile, '.') === false)
                    $this->readFiles($sPath . '/' . $sFile, $mFiles);
                else
                    $mFiles[] = $sPath . '/' . $sFile;
            }
        }
        closedir($rHandle);
        return $mFiles;
    }

    /**
     * Reading Directories.
     *
     * @param string $sPath
     * @return mixed (array | boolean) Returns an array with the folders or false if the folder could not be opened.
     */
    public function readDirs($sPath = './')
    {
        if (!($rHandle = opendir($sPath))) return false;
        $aRet = array();

        while (false !== ($sFolder = readdir($rHandle)))
        {
            if ('.' == $sFolder || '..' == $sFolder || !is_dir($sPath . $sFolder))
                continue;
            $aRet[] = $sFolder;
        }
        closedir($rHandle);
        return $aRet;
    }

    /**
     * Get the URL contents (For URLs, it is better to use CURL because it is faster than file_get_contents function).
     *
     * @param string $sFile URL to be read contents.
     * @return mixed (string | boolean) Return the result content on success, FALSE on failure.
     */
    public function getUrlContents($sFile)
    {
        $rCh = curl_init();
        curl_setopt($rCh, CURLOPT_URL, $sFile);
        curl_setopt($rCh, CURLOPT_HEADER, 0);
        curl_setopt($rCh, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($rCh, CURLOPT_FOLLOWLOCATION, 1);
        $mRes = curl_exec($rCh);
        curl_close($rCh);
        unset($rCh);

        return $mRes;
    }

    /**
     * Check if the file is binary.
     *
     * @param string $sFile
     * @return boolean
     */
    public function isBinary($sFile)
    {
        if (file_exists($sFile))
        {
            if (!is_file($sFile))
                return 0;

            if (preg_match('/^(.*?)\.(gif|jpg|jpeg|png|ico|mp3|mp4|mov|avi|flv|mpg|mpeg|wmv|ogg|ogv|webm|pdf|ttf|eot|woff|svg|swf)$/i', $sFile))
                return 1;

            $rHandle  = fopen($sFile, 'r');
            $sContents = fread($rHandle, 512); // Get 512 bytes of the file.
            fclose($rHandle);
            clearstatcache();

            if (!function_exists('is_binary')) // PHP 6
                return is_binary($sContents);

            return (
                0 or substr_count($sContents, "^ -~", "^\r\n")/512 > 0.3
                or substr_count($sContents, "\x00") > 0
            );
        }
        return 0;
    }

    /**
     * Create a recurive directory iterator for a given directory.
     *
     * @param string $sPath
     * @return string The directory.
     */
    public function getDirectoryIterator($sPath)
    {
        return (new \RecursiveDirectoryIterator($sPath));
    }

}