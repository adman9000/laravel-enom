<?php namespace otostudios\enomapi;

class EnomAPI {

    protected $tld;
    protected $sld;
    protected $url;
    protected $host;
    protected $domain;
    protected $scheme;
    protected $path;
    protected $urlAttributes;
    protected $xmlResponse;

    public function setDomain($domain) 
    {
        return $this->parseUrl($domain);
    } // setDomain

    public function parseUrl($domain) 
    {
        $parts = parse_url($domain);
        $split = explode('.', str_replace('www.','',$parts['host']));

        $this->domain = $domain;
        $this->host = $parts['host'];
        $this->scheme = $parts['scheme'];
        $this->path = array_key_exists('path', $parts) ? $parts['path'] : null;
        $this->sld = $split[0];
        $this->tld = $split[1];

        return true;
    } // parseUrl


    public function checkAvailability() 
    {   
        $this->apiCall('check', ['sld' => $this->sld, 'tld' => $this->tld]);

        return $this->getLastResponseCode() === 210;

    } // checkAvailability

    private function apiCall($command, $attributes = array())
    {
        foreach($attributes as $key => $value)
        {
            $this->urlAttributes .= "&{$key}={$value}";
        }

        $this->xmlResponse =  simplexml_load_file(config('enomapi.api_url') . "command={$command}&{$this->urlAttributes}&responsetype=xml&uid=".config('enomapi.enom_username') ."&pw=".config('enomapi.enom_password'));
    } // apiCall

    /**
     * Returns last XML Response Code
     */
    public function getLastResponseCode() 
    {
        return (int)$this->xmlResponse->RRPCode;
    } // getLastResponseCode
  

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

    public function hello() 
    {
        return config('enomapi.api_url');
    } // hello
}
