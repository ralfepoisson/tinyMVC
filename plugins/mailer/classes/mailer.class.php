<?php
/**
 * tinyMVC Plugin: Mailer Class
 *
 * @author Ralfe Poisson <ralfepoisson@gmail.com>
 */
class Mailer {

    /**
     * @param string[] $to
     * @param string $subject
     * @param string $body
     * @param string $from_name
     * @param string $from_email
     * @param string[] $attachments
     * @return bool
     */
    public static function Mail($to, $subject, $body, $from_name=null, $from_email=null, $attachments=null) {
        // Validate
        Preconditions::CheckNotNull($to, "to");
        Preconditions::CheckHasItems($to, "to");
        Preconditions::CheckNotBlank($subject, "subject");
        Preconditions::CheckNotBlank($body, "body");

        // Create PHPMailer Instance
        $email = new PHPMailer();

        // Set Parameters
        $email->Subject = $subject;
        $email->Body = $body;
        $email->From = $from_email;
        $email->FromName = $from_name;

        // Add To Addresses
        foreach ($to as $address) {
            $email->AddAddress($address);
        }

        // Add Attachments
        if ($attachments != null && is_array($attachments) && sizeof($attachments) > 0) {
            foreach ($attachments as $attachment) {
                $file_to_attach = 'PATH_OF_YOUR_FILE_HERE';
                $email->AddAttachment($file_to_attach , 'NameOfFile.pdf');
            }
        }

        // Send the Email
        $result = $email->Send();

        // Return the Result
        return $result;
    }

}
