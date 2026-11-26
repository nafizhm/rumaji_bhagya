<?php defined('BASEPATH') or exit('No direct script access allowed');
/**


 * CodeIgniter PDF Library
 *
 * Generate PDF's in your CodeIgniter applications.
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Chris Harvey
 * @license         MIT License
 * @link            https://github.com/chrisnharvey/CodeIgniter-  PDF-Generator-Library



 */

require_once APPPATH . 'libraries/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Pdf extends DOMPDF
{
    /**
     * Get an instance of CodeIgniter
     *
     * @access  protected
     * @return  void
     */
    protected function ci()
    {
        return get_instance();
    }

    /**
     * Load a CodeIgniter view into domPDF
     *
     * @access  public
     * @param   string  $view The view to load
     * @param   array   $data The view data
     * @return  void
     */
    public function load_view($view, $data = array(), $filename, $orentation = '')
    {

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isRemoteEnabled', TRUE);
        $dompdf = new Dompdf($options);
        $dompdf->set_option('isHtml5ParserEnabled', true);
        //$dompdf = new Dompdf();
        $html = $this->ci()->load->view($view, $data, TRUE);

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        if($orentation == 'l')
        {
            $dompdf->setPaper('A4', 'landscape');
        }
        else{
            $dompdf->setPaper('A4', 'portrait');
        }
      

        // Render the HTML as PDF
        $dompdf->render();
        $time = time();

        // Output the generated PDF to Browser
        //$dompdf->stream("welcome-". $time);
        $dompdf->stream($filename, array("Attachment" => false));
    }
}
