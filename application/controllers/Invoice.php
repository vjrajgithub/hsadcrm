<?php

class Invoice extends CI_Controller {

  use Dompdf\Dompdf;
  use Dompdf\Options;

  public function __construct() {
    parent::__construct();
    $this->load->model(['Invoice_model', 'Buyer_model', 'Product_model', 'Settings_model']);
    if (!$this->session->userdata('user'))
      redirect('login');
  }

  public function index() {
    $filters = $this->input->get();
    $data['invoices'] = $this->Invoice_model->get_all($filters);
    $data['buyers'] = $this->Buyer_model->get_all();
    $this->load->view('layouts/main_header');
    $this->load->view('layouts/navbar');
    $this->load->view('layouts/sidebar');
    $this->load->view('layouts/content_wrapper_open');
    $this->load->view('invoice/index', $data);
    $this->load->view('layouts/content_wrapper_close');
    $this->load->view('layouts/footer');
  }

  public function create() {
    $data['buyers'] = $this->Buyer_model->get_all();
    $data['products'] = $this->Product_model->get_all();
    $this->load->view('invoice/create', $data);
  }

  public function store() {
    $invoice = $this->input->post('invoice');
    $items = $this->input->post('items');
    $id = $this->Invoice_model->insert($invoice, $items);
    redirect('invoice/view/' . $id);
  }

  public function edit($id) {
    $data['invoice'] = $this->Invoice_model->get($id);
    $data['items'] = $this->Invoice_model->get_items($id);
    $data['buyers'] = $this->Buyer_model->get_all();
    $data['products'] = $this->Product_model->get_all();
    $this->load->view('invoice/edit', $data);
  }

  public function update($id) {
    $invoice = $this->input->post('invoice');
    $items = $this->input->post('items');
    $this->Invoice_model->update($id, $invoice, $items);
    redirect('invoice/view/' . $id);
  }

  public function delete($id) {
    $this->Invoice_model->delete($id);
    redirect('invoice');
  }

  public function view($id) {
    $data['invoice'] = $this->Invoice_model->get($id);
    $data['items'] = $this->Invoice_model->get_items($id);
    $data['buyer'] = $this->Buyer_model->get($data['invoice']->buyer_id);
    $data['settings'] = $this->Settings_model->get_all();
    $this->load->view('invoice/view', $data);
  }

  public function export_excel() {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=invoices_" . date('Ymd') . ".xls");

    $filters = $this->input->get();
    $invoices = $this->Invoice_model->get_all($filters);

    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Date</th><th>No</th><th>Buyer</th><th>Subtotal</th><th>GST</th><th>Total</th></tr>";
    foreach ($invoices as $i) {
      echo "<tr>
            <td>{$i->id}</td>
            <td>{$i->invoice_date}</td>
            <td>{$i->invoice_no}</td>
            <td>{$i->buyer_name}</td>
            <td>{$i->subtotal}</td>
            <td>{$i->total_gst}</td>
            <td>{$i->total}</td>
        </tr>";
    }
    echo "</table>";
  }

  public function pdf($id) {
    $data['invoice'] = $this->Invoice_model->find($id);
    $data['buyer'] = $this->Buyer_model->find($data['invoice']->buyer_id);
    $data['items'] = $this->Invoice_model->get_items($id);
    $data['settings'] = $this->Settings_model->get_settings();

    $html = $this->load->view('invoice/view', $data, true);

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $pdf = new Dompdf($options);
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();
    $pdf->stream("invoice_" . $data['invoice']->invoice_no . ".pdf", ["Attachment" => false]);
  }

  public function send($id) {
    $data['invoice'] = $this->Invoice_model->find($id);
    $data['buyer'] = $this->Buyer_model->find($data['invoice']->buyer_id);
    $data['items'] = $this->Invoice_model->get_items($id);
    $data['settings'] = $this->Settings_model->get_settings();

    $html = $this->load->view('invoice/view', $data, true);

    // Generate PDF
    $options = new Dompdf\Options();
    $options->set('isRemoteEnabled', true);
    $pdf = new Dompdf\Dompdf($options);
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();
    $pdf_output = $pdf->output();

    $filename = 'invoice_' . $data['invoice']->invoice_no . '.pdf';
    file_put_contents(FCPATH . 'uploads/' . $filename, $pdf_output);

    // Load email library
    $this->load->library('email');
    $this->email->from('your@email.com', 'Your Company');
    $this->email->to($data['buyer']->email);
    $this->email->subject('Invoice #' . $data['invoice']->invoice_no);
    $this->email->message('Dear ' . $data['buyer']->name . ",<br><br>Please find your invoice attached.<br><br>Thanks,<br>Team");
    $this->email->attach(FCPATH . 'uploads/' . $filename);

    if ($this->email->send()) {
      unlink(FCPATH . 'uploads/' . $filename); // Delete temp PDF
      $this->session->set_flashdata('success', 'Invoice sent successfully!');
    } else {
      $this->session->set_flashdata('error', 'Failed to send invoice.');
    }

    redirect('invoice/view/' . $id);
  }

}
