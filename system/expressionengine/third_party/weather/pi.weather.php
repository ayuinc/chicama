<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Memberlist Class
 *
 * @package     ExpressionEngine
 * @category    Plugin
 * @author      Ricardo Díaz 
 * @copyright   Copyright (c) 2014, Ricardo Díaz
 * @link        http://www.ayuinc.com/
 */

$plugin_info = array(
    'pi_name'         => 'Weather',
    'pi_version'      => '1.0',
    'pi_author'       => 'Ricardo Díaz',
    'pi_author_url'   => 'http://www.ayuinc.com/',
    'pi_description'  => 'Allow extract surf data from MagicSeaWeed API',
    'pi_usage'        => Weather::usage()
);
            
class Weather 
{

    var $return_data = "";
    // --------------------------------------------------------------------

        /**
         * Memberlist
         *
         * This function returns a list of members
         *
         * @access  public
         * @return  string
         */
    public function __construct(){
    }

    // --------------------------------------------------------------------

    /**
     * Usage
     *
     * This function describes how the plugin is used.
     *
     * @access  public
     * @return  string
     */
    public static function usage()
    {
        ob_start();  ?>
        The Memberlist Plugin simply outputs a
        list of 15 members of your site.

            {exp:weather}

        This is an incredibly simple Plugin.
            <?php
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
    // END

    public function data(){
        $url = "http://magicseaweed.com/api/3XpBW72Em3wuAo7O0BYc17k582W308Ek/forecast/?spot_id=416&units=eu";
        //  Initiate curl
        $ch = curl_init($url);
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);

        // Will dump a beauty json :3
        $data = json_decode($result, true);

        date_default_timezone_set('GMT');

        $hour = (int) date('h', time()) + 7; 

        if ($hour >= 0 && $hour < 3) {
            $data = $data[0];
        } else if ($hour >= 3 && $hour < 6 ) {
            $data = $data[1];
        } else if ($hour >= 6 && $hour < 9 ) {
            $data = $data[2];
        } else if ($hour >= 9 && $hour < 12 ) { 
            $data = $data[3];
        } else if ($hour >= 12 && $hour < 15 ) { 
            $data = $data[4];
        } else if ($hour >= 15 && $hour < 18 ) { 
            $data = $data[5];
        } else if ($hour >= 18 && $hour < 21 ) { 
            $data = $data[6];
        } else {
            $data = $data[7];
        }

        // if($data["condition"]["unit"] == "f") {
        //     $data["condition"]["temperature"] = $data["condition"]["temperature"] * 1.8 + 32;
        //     $data["condition"]["temperature"] = round($data["condition"]["temperature"], 2);
        // }

        // if($data["swell"]["unit"] == "ft") {
        //     $data["swell"]["absMinBreakingHeight"] = $data["swell"]["absMinBreakingHeight"] * 0.3048;
        //     $data["swell"]["absMaxBreakingHeight"] = $data["swell"]["absMaxBreakingHeight"] * 0.3048;

        //     $data["swell"]["absMinBreakingHeight"] = round($data["swell"]["absMinBreakingHeight"], 2);
        //     $data["swell"]["absMaxBreakingHeight"] = round($data["swell"]["absMaxBreakingHeight"], 2);
        // }

        // if($data["wind"]["unit"] == "mph") {
        //     $data["wind"]["speed"] = $data["wind"]["speed"] * 1.609344;
        //     $data["wind"]["speed"] = round($data["wind"]["speed"], 2);
        // }

        $text = '<div class="weather row text-center">
                    <div class="large-4 columns">
                        <div class="weather_icon"> 
                            <img src="http://64.207.145.174/img/surf_height_icon.png" alt="">
                        </div>
                        <div class="weather_title">
                            <span>SURF HEIGHT</span>
                        </div>
                        <span>' . $data["swell"]["absMinBreakingHeight"] . ' - ' . $data["swell"]["absMaxBreakingHeight"] . '</span>
                        <span>' . $data["swell"]["unit"] . '</span>
                    </div>
                                
                    <div class="large-4 columns">
                        <div class="weather_icon"> 
                            <img src="http://64.207.145.174/img/wind_icon.png" alt="">
                        </div>
                        <div class="weather_title">
                            <span>CURRENT WIND</span>
                        </div>
                        <span>' . $data["wind"]["speed"] . '</span>
                        <span>' . $data["wind"]["unit"] . '</span>
                    </div>
                    <div class="large-4 columns">
                        <div class="weather_icon"> 
                            <img src="http://64.207.145.174/img/current_tide_icon.png" alt="">
                        </div>
                        <div class="weather_title">
                            <span>CURRENT TIDE</span>
                        </div>
                        <span>' . $data["condition"]["temperature"] . '</span>
                        <span>' . $data["condition"]["unit"] . '</span>
                    </div>
                </div>';

        return $text;
    }

}
/* End of file pi.weather.php */
/* Location: ./system/expressionengine/third_party/weather/pi.weather.php */