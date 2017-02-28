<?php

namespace 131studios\enomapi;

use App\Http\Controllers\Controller;

use App\Domains;
use Event;
use App\Events\WebsiteWasCreated;
use App\Http\Requests;
use App\Http\Requests\StoreWebsite;
use Illuminate\Http\Request;


class EnomController extends Controller
{
  private $enomUrl;

	/**
   * Constructor
   */
	public function __construct() 
	{
	 
    if(env('APP_ENV') != 'production')
    {
      $this->enomUrl = 'https://resellertest.enom.com';
    }
    else
    {
      $this->enomUrl = 'https://enom.com';
    }

	} // __construct
  
    /**
     * Verify Url against ENOM API
     * @param Request $request 
     */
    public function verifyUrl(Request $request) 
    {
        $this->validate($request, [
              'url' =>  'required|string',
              'tld' =>  'required|string',
              'sitetype' => 'required|integer',
              'domainType' => 'required|integer',
        ]);

        // Verify URL
        if(! $this->isValidURL($request->url, $request->tld))
        {
          return [
            'success' =>  0,
            'errorcode' =>  1,
            'errormsg'  =>  'Invalid domain name',
          ];
        }
        
        // Local ENV bypass
        if(env('APP_ENV') == 'local') 
        {
          return [
            'success'  =>  1,
            'available' => true,
          ];
        }

        // URL for API request
        // make sure you use the correct url
        $xml = simplexml_load_file($this->enomUrl .'/interface.asp?command=check&sld='.$request->url.'&tld='.$request->tld.'&responsetype=xml&uid='.ENV('ENOM_USER').'&pw='.ENV('ENOM_PASSWORD'));
        
        // Perform actions based on results
        switch ($xml->RRPCode) 
        {
          case 210:
           return [
            'success' =>  1,
            'available' =>  true,
           ];
            break;
          
          case 211:
            return [
              'success' =>  1,
              'available' =>  false,
            ];
            break;
          
          default:
            return [
                'success' =>  9,
                'error'   =>  $xml->RRPCode . ' ' . $xml->RRPText,
            ];
            break;
        }
        
    } 


    // /**
    //  * Purchase Enom Domain
    //  * @param Request $request 
    //  */ 
    // public function PurchaseEnomDomain(StoreWebsite $request) 
    // {
    //   // Local ENV bypass
    //   if(env('APP_ENV') == 'local' || $request->tld == 'APP') 
    //   {
    //     return [
    //       'success' =>  1,
    //     ];
    //   }

    //   // API URL
    //   if(env('APP_ENV') != 'production')
    //   {
    //     $urlargs = [
    //       'domain' => 'https://resellertest.enom.com',
    //       'dns' =>  'UseDNS=default'
    //       ];
    //   }
    //   else
    //   {
    //     $urlargs = [
    //       'domain'  =>  'https://enom.com',
    //       'dns' =>  'NS1=ns1.131studios.com&NS2=ns2.131studios.com'
    //     ];
    //   }

    //   // Validate URL
    //   if(! $this->isValidURL($request->url, $request->tld))
    //   {
    //     return [
    //       'success' =>  0,
    //       'errorcode' =>  1,
    //       'errormsg'  =>  'Invalid domain name',
    //     ];
    //   }
      
    //   $purchaseurl = simplexml_load_file( $this->enomUrl .'/interface.asp?Command=Purchase&UID='.ENV('ENOM_USER').'&PW='.ENV('ENOM_PASSWORD').'&SLD='.$request->url.'&TLD='.$request->tld.'&'.$urlargs['dns'].'&NumYears=1&ResponseType=XML');

    //   // Perform actions based on results
    //   switch ($purchaseurl->RRPCode) 
    //   {
    //     case 200:
    //       // Store the website
    //       $website = new Website;
    //       $website->type = $request->sitetype;
    //       $website->user_id = auth()->user()->id;
    //       $website->domain = $request->url . '.' . strtolower($request->tld);
    //       $website->save();

    //       if(env('APP_ENV') != 'local' && $request->sitetype == 0)
    //       {
    //         // Store the domain order if we have one
    //         $newdomain = new Domains;

    //         $newdomain->order_id = $purchaseurl->OrderID;
    //         $newdomain->domain = $request->url . '.' . $request->tld;
    //         $newdomain->website_id = $website->id;
    //         $newdomain->total_charged = $purchaseurl->TotalCharged;

    //         $newdomain->save();
    //       }

    //       Event::fire(new WebsiteWasCreated($website));

    //       $request->session()->flash('type', $request->sitetype);

    //       return [
    //         'success' =>  1,
    //         'id'  =>  $website->id,
    //       ];
    //       break;
    //     default:
    //       return [
    //         'success' =>  0,
    //       ];
    //       break;
    //   }
    // } // PurchaseEnomDomain
    

    // /**
    //  * Show Terms of Service/Purchase
    //  */
    // public function ShowTerms() 
    // {
    //   $xml = simplexml_load_file( $this->enomUrl .'/interface.asp?Command=GetAgreementPage&UID='.ENV('ENOM_USER').'&PW='.ENV('ENOM_PASSWORD').'&ResponseType=XML');

    //   echo $xml->content;
    // } // ShowTerms
}
