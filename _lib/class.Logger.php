<?


/**
 * The contents of this file cannot be copied, distributed or modified without prior
 * consent of the author.
 *
 * Project : RenderUP
 * File :  class.Logger.php
 *
 * @author Pranjal Goswami <pranjal[dot]b[dot]goswami[at]gmail[dot]com>
 */

class Logger
{

    const INFO = 0;

    const ERROR = 1;

    const SUCCESS = 2;

    /* Resource - open file pointer */
    var $log = null;

    /* Boolean to control Logging */
    var $_log = true;

    /* Log Name */
    var $log_name;

    /* Static Logger Instances */
    private static $loggers = array();

    /* Class Constructor */
    public function __construct($location, $log_name = 'def_log')
    {

        if ($location != false) {
            
            $this->log = $this->openFile($location, 'a');
            
        }
        $this->log_name = $log_name;
    }

    /**
     * The singleton constructor
     */
    public static function getInstance($log_location = null)
    {
        if (! $log_location) {
            $log_location = 'error.log'; // the default log location
        }
        
        if (! isset(self::$loggers[$log_location])) {
            $log = new Logger($log_location);
            self::$loggers[$log_location] = $log;
        }
        return self::$loggers[$log_location];
    }

    /**
      * Setter for $_log
      */
    public function setLog($should){
        $_log = $should ? true : false;
    }

    /**
     * Write to log
     * 
     * @param str $status_message            
     * @param str $classname
     *            The name of the class logging the info
     */
    public function logStatus($status_message, $classname, $type = self::INFO, $html = false)
    {
        if ($html) {
            $message_wrapper = '<tr><td><small>' . date("Y/m/d H:i", time()) . '</small></td>';
            $message_wrapper .= '<td>' . $classname . '</td><td class="crawl-log-component">';
            $message_wrapper .= '</td> <td class="';
            switch ($type) {
                case self::INFO:
                    $message_wrapper .= 'control-group info">';
                    break;
                case self::ERROR:
                    $message_wrapper .= 'control-group error">';
                    break;
                case self::SUCCESS:
                    $message_wrapper .= 'control-group success">';
                    break;
                default:
                    $message_wrapper .= 'control-group warning">';
            }
            if (strlen($status_message) > 0) {
                $this->output($message_wrapper . $status_message . '</td></tr>'); // Write status to log
            }
        } else {
            $message_wrapper = '[' . date("Y/m/d H:i:s P", time()) . '] ';
            $message_wrapper .= '(' . $classname . ') ';
            switch ($type) {
                case self::INFO:
                    $message_wrapper .= '[INFO] ';
                    break;
                case self::ERROR:
                    $message_wrapper .= '[ERROR] ';
                    break;
                case self::SUCCESS:
                    $message_wrapper .= '[SUCCESS] ';
                    break;
                default:
                    $message_wrapper .= '[INFO] ';
            }
            if (strlen($status_message) > 0) {
                $this->output($message_wrapper . $status_message); // Write status to log
            }
            
        }
    }

    protected function openFile($filename, $type)
    {
        
        if (array_search($type, array(
            'w',
            'a'
        )) < 0) {
            $type = 'w';
        }
        
        $filehandle = null;
        if (is_writable($filename) || is_writable(dirname($filename))) {
            $filehandle = fopen($filename, $type) or die("can't open file $filename");
        } else {
            error_log("Unable to write log file: " . $filename);
        }
        
        return $filehandle;
    }

    /**
     * Output log message to file or terminal
     * 
     * @param str $message            
     */
    protected function output($message)
    {
        
        if (isset($this->log)) {
            return fwrite($this->log, $message . "\n" . PHP_EOL);
        } else {
            echo $message . '
';
            @flush();
        }
    }

    protected function addBreak()
    {
        $this->output("");
    }

    public function logInfo($status_message, $classname = "render")
    {
        $this->logStatus($status_message, $classname, self::INFO);
    }

    public function logError($status_message, $classname = "render")
    {
        $this->logStatus($status_message, $classname, self::ERROR);
    }

    public function logSuccess($status_message, $classname = "render")
    {
        $this->logStatus($status_message, $classname, self::SUCCESS);
    }

    protected function closeFile($filehandle)
    {
        if (isset($filehandle)) {
            return fclose($filehandle);
        }
    }

    protected function deleteFile($filename)
    {
        return unlink($filename);
    }
}
?>