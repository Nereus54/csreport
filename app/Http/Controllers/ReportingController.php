<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\RestClient as RestAPI;
use App\Models\ReportingAPI as RepAPI;
use App\Http\Helpers\DateFilterHelper as Df_helper;

class ReportingController extends Controller
{
	protected $_reporting;
	
	protected $_default_dateFrom;
	protected $_default_dateTo;


	protected function _init()
	{
		if ( null === $this->_reporting )
		{
			$base_url	= config('app.reporting_api.base_url');
			$login_url	= config('app.reporting_api.login_url');
			$email		= config('app.reporting_api.email');
			$paswd		= config('app.reporting_api.passwd');
			
			$this->_reporting = RepAPI::getInstance()->setUp($base_url, $email, $paswd, $login_url)->login();
		}
		else
		{
			$this->_reporting = RepAPI::getInstance()->keepAlive();
		}
		
		$this->_default_dateFrom = config('app.default_date.from');
		$this->_default_dateTo = config('app.default_date.to');
	}
	
    public function index()
	{
		return redirect('report');
	}
	
	public function report($fromDate = '', $toDate = '')
	{
		$this->_init();
		
		$df_helper = new Df_helper();
		list($fromDate, $toDate) = $df_helper->dateFilterHelper($fromDate, $toDate, $this->_default_dateFrom, $this->_default_dateTo);
		
		$headers = array(
			'Authorization' => $this->_reporting->getToken(),
		);

		$params = array(
			"fromDate"	=> $fromDate,
			"toDate"	=> $toDate,
		);

		/**
		 * Transaction report
		 */
		$transaction_report = $this->_reporting->post("/api/v3/transactions/report", $params, $headers)->getResponseBody(true);
		
		return view('report', [
			'data' => $transaction_report['response'],
			'fromDate' => date('d/m/Y', strtotime($fromDate)),
			'toDate' => date('d/m/Y', strtotime($toDate)),
		]);
	}
	
	public function ajaxReport(Request $request)
	{
		$this->_init();
		
		$data = $request->all();
		
		$fromDate = $data['fromDate'];
		$toDate = $data['toDate'];
		
		$df_helper = new Df_helper();
		list($fromDate, $toDate) = $df_helper->dateFilterHelper($fromDate, $toDate, $this->_default_dateFrom, $this->_default_dateTo);
		
		$headers = array(
			'Authorization' => $this->_reporting->getToken(),
		);

		$params = array(
			"fromDate"	=> $fromDate,
			"toDate"	=> $toDate,
		);

		/**
		 * Transaction report
		 */
		$transaction_report = $this->_reporting->post("/api/v3/transactions/report", $params, $headers)->getResponseBody(true);
		
		return response()->json(
			array(
				'fromDate' => date('d/m/Y', strtotime($fromDate)),
				'toDate' => date('d/m/Y', strtotime($toDate)),
				'body' => $transaction_report['response'],
			), 
		200);
	}
	
	public function viewlist($fromDate = '', $toDate = '', $page = 1)
	{
		$this->_init();
		
		$df_helper = new Df_helper();
		list($fromDate, $toDate) = $df_helper->dateFilterHelper($fromDate, $toDate, $this->_default_dateFrom, $this->_default_dateTo);
		
		$headers = array(
			'Authorization' => $this->_reporting->getToken(),
		);

		$page = (int)$page;
		$page = ( !empty($page) ) ? $page : 1;
		
		$params = array(
			"fromDate"	=> $fromDate,
			"toDate"	=> $toDate,
			"page"		=> $page,
		);
		
		/**
		 * Transaction list
		 */
		$transaction_list = $this->_reporting->post("/api/v3/transaction/list", $params, $headers)->getResponseBody(true);
		
		return view('list', [
			'data' => $transaction_list,
			'fromDate' => date('d/m/Y', strtotime($fromDate)),
			'toDate' => date('d/m/Y', strtotime($toDate)),
		]);
	}
	
	public function ajaxList(Request $request)
	{
		$this->_init();
		
		$data = $request->all();
		
		$fromDate = $data['fromDate'];
		$toDate = $data['toDate'];
		$page = $data['page'];
		
		$df_helper = new Df_helper();
		list($fromDate, $toDate) = $df_helper->dateFilterHelper($fromDate, $toDate, $this->_default_dateFrom, $this->_default_dateTo);
		
		$headers = array(
			'Authorization' => $this->_reporting->getToken(),
		);

		$page = (int)$page;
		$page = ( !empty($page) ) ? $page : 1;
		
		$params = array(
			"fromDate"	=> $fromDate,
			"toDate"	=> $toDate,
			"page"		=> $page,
		);
		
		/**
		 * Transaction list
		 */
		$transaction_list = $this->_reporting->post("/api/v3/transaction/list", $params, $headers)->getResponseBody(true);
		
		return response()->json(
			array(
				'fromDate' => date('d/m/Y', strtotime($fromDate)),
				'toDate' => date('d/m/Y', strtotime($toDate)),
				'body' => $transaction_list['data'],
			), 
		200);
	}

	public function viewTransaction($id)
	{
		$this->_init();
		
		$headers = array(
			'Authorization' => $this->_reporting->getToken(),
		);
		
		$params = array(
			'transactionId' => $id,
		);
		
		$result = $this->_reporting->post("/api/v3/transaction", $params, $headers)->getResponseBody(true);
		
		return view('transaction', [
			'data' => $result,
		]);
	}
	
	public function viewClient($id)
	{
		$this->_init();
		
		$headers = array(
			'Authorization' => $this->_reporting->getToken(),
		);
		
		$params = array(
			'transactionId' => $id,
		);
		
		$result = $this->_reporting->post("/api/v3/client", $params, $headers)->getResponseBody(true);
		
		return view('client', [
			'data' => $result,
		]);
	}
	
	public function viewMerchant($id)
	{
		$this->_init();
		
		$headers = array(
			'Authorization' => $this->_reporting->getToken(),
		);
		
		$params = array(
			'transactionId' => $id,
		);
		
		$result = $this->_reporting->post("/api/v3/merchant", $params, $headers)->getResponseBody(true);
		
		return view('merchant', [
			'data' => $result,
		]);
	}
}
