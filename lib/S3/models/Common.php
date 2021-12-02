<?php
error_reporting(E_ERROR);

//error_reporting(E_ERROR);
use AWS\S3\Exception\S3Exception;
use Aws\S3\S3Client;

require APPPATH . 'libraries/app/s3config.php';

//define('key', $config_s3['s3']['key']);
class Common extends s3config
{
    public function __construct()
    {
        parent::__construct();
        $this->s3 = S3Client::factory([
            'region' => 'us-east-1',
            'version' => '2006-03-01',
            'signature_version' => 'v4',
            'credentials' => [
            'key' => $this->s3val['s3']['key'],
            'secret' => $this->s3val['s3']['secret'],
            ],
            ]);
    }

    /* S3 Image Upload */
    
    public function image_upload_S3($field, $path, $field1, $path1)
    {
        $tmp = explode('.', $_FILES[$field]['name']);
        $ext = end($tmp);
        if (in_array($ext, $this->valid_formats)) {
            $image_name = uniqid() . strtotime(date("Ymd his")) . "." . $ext;

            try
            {
                $this->s3->putObject([
                    'Bucket' => $this->s3val['s3']['bucket'],
                    'Key' => $path . $image_name,
                    'SourceFile' => $_FILES[$field]['tmp_name'],
                    'ServerSideEncryption' => 'AES256',
                    'ACL' => 'public-read',
                    ]);

                $this->s3->putObject([
                    'Bucket' => $this->s3val['s3']['bucket'],
                    'Key' => $path1 . $image_name,
                    'SourceFile' => $_FILES[$field1]['tmp_name'],
                    'ServerSideEncryption' => 'AES256',
                    'ACL' => 'public-read',
                    ]);
                return $image_name;
            } catch (S3Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    public function media_thumb_upload_web_S3($field, $path, $image_name = '')
    {
        //print_r($s3);die();
        //print_r($_FILES[$field]);die();

        $path_temp = "./assets/images/temp/";

        $tmp = explode('.', $_FILES[$field]['name']);
        $ext = end($tmp);
        if (in_array($ext, $this->photo_valid_formats)) {
            if ($image_name == '') {
                $image_name = uniqid() . strtotime(date("Ymd his")) . "." . $ext;
            }

            $config = array(
                'image_library' => 'gd2',
                'new_image' => $path_temp . 'thumb/',
                'source_image' => $path_temp . $image_name,
                'create_thumb' => false,
                'width' => "350",
                'height' => "350",
                'maintain_ratio' => true,
                );

            $this->load->library('image_lib'); // add liberary
            $this->image_lib->initialize($config);
            $this->image_lib->resize();

            $exif = exif_read_data($path_temp . $image_name);
            /*echo "<pre>";
            print_r($exif);die();*/
            if ($exif && isset($exif['Orientation'])) {
                $config1 = array(
                    'image_library' => 'gd2',
                    'new_image' => $path_temp . 'thumb/',
                    'source_image' => $path_temp . 'thumb/' . $image_name,
                    );

                $ort = $exif['Orientation'];

                if ($ort == 6 || $ort == 5) {
                    $config1['rotation_angle'] = '270';
                }

                if ($ort == 3 || $ort == 4) {
                    $config1['rotation_angle'] = '180';
                }

                if ($ort == 8 || $ort == 7) {
                    $config1['rotation_angle'] = '90';
                }

                $this->load->library('image_lib'); // add library
                $this->image_lib->initialize($config1);
                $this->image_lib->rotate();
            }

            try
            {
                $this->s3->putObject([
                    'Bucket' => $this->s3val['s3']['bucket'],
                    'Key' => $path . $image_name,
                    'SourceFile' => 'assets/images/temp/thumb/' . $image_name,
                    'ServerSideEncryption' => 'AES256',
                    //'Body'        => fopen($temp_name, 'rb'),
                    'ACL' => 'public-read',
                    ]);

                @unlink("./assets/images/temp/" . $image_name);
                @unlink("./assets/images/temp/thumb/" . $image_name);

                return $image_name;

            } catch (S3Exception $e) {
                return false;
            }

        } else if (in_array($ext, $this->video_valid_formats)) {
            $blank_name = uniqid() . strtotime(date("Ymd his"));
            $image_name = $blank_name . "." . $ext;

            if (move_uploaded_file($_FILES[$field]['tmp_name'], $path_temp . $image_name)) {
                $thumbnail_path = $path_temp . 'thumb/';
                $second = 1;
                $thumbSize = '350x350';

                // Video file name without extension(.mp4 etc)
                $videoname = $blank_name . '.jpg';
                $video_file_path = $path_temp . $image_name;
                // FFmpeg Command to generate video thumbnail

                $cmd = "ffmpeg -i {$video_file_path} -deinterlace -an -ss {$second} -t 00:00:01  -s {$thumbSize} -r 1 -y -vcodec mjpeg -f mjpeg {$thumbnail_path}{$videoname} 2>&1";
                exec($cmd);

                try
                {
                    $this->s3->putObject([
                        'Bucket' => $this->s3val['s3']['bucket'],
                        'Key' => $path . $image_name,
                        'SourceFile' => 'assets/images/temp/thumb/' . $videoname,
                        'ServerSideEncryption' => 'AES256',
                        'ACL' => 'public-read',
                        ]);

                    @unlink("./assets/images/temp/" . $image_name);
                    @unlink("./assets/images/temp/thumb/" . $videoname);

                    return $image_name;

                } catch (S3Exception $e) {
                    return false;
                }

            } else {
                return false;
            }
        } else {
            return false;
        }

    }

}
