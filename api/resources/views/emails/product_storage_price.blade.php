<!DOCTYPE html>

<html>



    <head>

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />



        <style type="text/css" rel="stylesheet" media="all">

            /* Media Queries */

            @media only screen and (max-width: 500px) {

                .button {

                    width: 100% !important;

                }

            }

        </style>

    </head>

    <?php
    $style = [
        /* Layout ------------------------------ */



        'body' => 'margin: 0; padding: 0; width: 100%;font-family: "Lato", sans-serif;',
        'email-wrapper' => 'width: 100%; margin: 0; padding: 0;',
        /* Masthead ----------------------- */
        'email-masthead' => 'padding: 0px 0 0; text-align: center;',
        'email-masthead-logo' => 'padding: 0 0 20px; text-align: center;',
        'email-masthead_name' => 'font-size: 16px; font-weight: bold; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white; display: inline-block; vertical-align: top; margin: 17px 20px 0 0',
        'email-body' => 'width: 100%; margin: 0; padding: 0; background-color: #FFF;',
        'email-body_inner' => 'width: auto; max-width: 570px; margin: 30px auto; padding: 0;',
        'email-body_cell' => 'padding: 15px 20px;',
        'email-footer' => 'width: auto; max-width: 570px; margin: 0 auto; padding: 0; text-align: center;',
        'email-footer_cell' => 'color: #AEAEAE; padding: 0px; text-align: center;',
        /* Body ------------------------------ */
        'body_action' => 'width: 100%; margin: 20px auto; padding: 0; text-align: center; background: #fff;',
        'body_sub' => 'margin-top: 25px; padding-top: 25px;',
        'body_box' => 'padding: 10px;font-family: "Lato", sans-serif;font-size: 14px; color:black; text-align: left;',
        'body_Box_Last' => ' padding: 15px;font-family: "Lato", sans-serif;font-size: 14px; color:black; text-align: center;',
        /* Type ------------------------------ */
        'anchor' => 'color: #3869D4;',
        'header-1' => 'margin-top: 0; color: rgb(223, 132, 98); font-family: "Lato", sans-serif;font-size: 24px; font-weight: 700; text-align: left;',
        'header-2_old' => 'margin-top: 0; color: #74787E; font-family: "Lato", sans-serif;font-size: 24px; font-weight: 700; text-align: left;',
        'header-2' => 'margin-top: 0; color: black; font-family: "Lato", sans-serif;font-size: 24px; font-weight: 700; text-align: left;',
        'paragraph_old' => 'margin-top: 0; color: #74787E; font-size: 14px; line-height: 1.5em;font-family: "Lato", sans-serif; ',
        'paragraph' => 'margin-top: 0; color: black; font-size: 14px; line-height: 1.5em;font-family: "Lato", sans-serif; ',
        'paragraph-blue' => 'margin-top: 0; color: #4285F4; font-size: 14px; line-height: 1.5em;font-family: "Lato", sans-serif; ',
        'paragraph-sub_old' => 'margin-top: 12px; color: #74787E; font-size: 12px; line-height: 1.5em;',
        'paragraph-sub' => 'margin-top: 12px; color: black; font-size: 12px; line-height: 1.5em;',
        'paragraph-center' => 'text-align: center;',
        /* Buttons ------------------------------ */
        'button' => 'display: block; display: inline-block; width: 200px; min-height: 20px; padding: 10px;

                 background-color: #3869D4; border-radius: 3px; color: #ffffff; font-size: 15px; line-height: 25px;

                 text-align: center; text-decoration: none; -webkit-text-size-adjust: none;',
        'button--green' => 'background-color: #22BC66;',
        'button--red' => 'background-color: #dc4d2f;',
        'button--blue' => 'background-color: #3869D4; ',
    ];
    ?>



    <?php $fontFamily = 'font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;'; ?>

    <body style="{{ $style['body'] }}">





        <table width="100%" cellpadding="0" cellspacing="0">



            <tr>

                <td style="{{ $style['email-wrapper'] }}" align="center">

                    <table width="100%" cellpadding="0" cellspacing="0">

                        <!-- Logo -->

                        <tr>

                            <td style="{{ $style['email-masthead'] }}">

                                <a style="{{ $fontFamily }} {{ $style['email-masthead_name'] }}" href="{{ url('/../') }}" target="_blank">

                                    <!-- {{ config('app.name') }} -->

                                    <img style="margin-top: 20px;" src="{{asset('public/assets/images/long-logo.png')}}" alt="" />

                                </a>



                            </td>

                        </tr>

<!--                        <tr>

                            <td style="{{ $style['email-masthead'] }}">

                            <h2><span>Pricing Proposal</span></h2>

                            </td>

                        </tr>-->

                        <tr>

                            <td style="{{ $style['email-body'] }}" width="100%">

                                <table style="{{ $style['email-body_inner'] }}" align="center" width="570" cellpadding="0" cellspacing="0">

                                    <tr>

                                        <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }}">

                                            <!-- Greeting -->



                                            @if (! empty($greeting))

                                            <p style="{{ $style['paragraph'] }}">

                                                <i>{{ $greeting }}</i>

                                            </p>

                                            @endif


                                            @foreach ($introLines as $line)

                                            <p style="{{ $style['paragraph'] }}">

                                                {{ $line }}

                                            </p>

                                            @endforeach





                                            @if (isset($agreement_link))

                                            <p style="{{ $style['paragraph'] }}">

                                                <a href="{{$agreement_link}}"><b>Click here</b></a> to complete and sign the Storage Agreement

                                            </p>

                                            @endif





                                            <!--//table approve here-->

                                            @if(count($product_quots)>0)



                                            <table border="0" style="{{ $style['body_action'] }}" align="center" width="100%" cellpadding="0" cellspacing="0">



                                                <tr>

                                                    <td align="center" style="{{ $style['body_box'] }}" >

                                                        <b>Image</b>

                                                    </td>

                                                    <td align="center" style="{{ $style['body_box'] }}" >

                                                        <b>SKU</b>

                                                    </td>

                                                    <td align="center" style="{{ $style['body_box'] }}" >

                                                        <b>Title</b>

                                                    </td>

                                                    <td align="center" style="{{ $style['body_box'] }}" >

                                                        <b>Storage Cost (monthly)</b>

                                                    </td>

                                                </tr>

                                                @foreach ($product_quots as $quote)

                                                <tr>

                                                    <td align="center" style="{{ $style['body_box'] }}" >

                                                        @if(count($quote->getProductId()->getProductPendingImages())>0)



                                                        <img style="height: 100px;width: 100px;margin: 10px;" src="{{ config('app.url') }}/Uploads/product/{{$quote->getProductId()->getProductPendingImages()[0]->getName()}}"/>

                                                        @endif

                                                    </td> 

                                                    <td align="center" style="{{ $style['body_box'] }}" >

                                                        {{$quote->getProductId()->getSku()}}

                                                    </td>

                                                    <td align="center" style="{{ $style['body_box'] }}" >

                                                        {{$quote->getProductId()->getName()}}

                                                    </td>


                                                    <td align="center" style="{{ $style['body_box'] }} width: 100px;" >

                                                        @if($quote->getStorage_pricing()!='')

                                                        <span>$ {{$quote->getStorage_pricing()}}</span>

                                                        @else

                                                        @endif


                                                    </td>

                                                </tr>

                                                @endforeach



                                            </table>

                                            <br>



                                            @endif



                                            <p style="{{ $style['paragraph'] }}">

                                                {{ $line1 }} <b>{{$line2}}</b>

                                            </p>

                                            <p style="{{ $style['paragraph'] }}">

                                                {{ $line3 }} 

                                            </p>



                                            <!-- Salutation -->

                                            <p style="{{ $style['paragraph'] }}">

                                                Cheers,<br>{{ config('app.name') }} Team

                                            </p> 
                                            <!--                                            @if (isset($agreement_link))
                                            
                                                                                        <p style="{{ $style['paragraph'] }}">
                                            
                                                                                            <a href="{{$agreement_link}}"><b>Click here</b></a> to complete and sign the Storage Agreement
                                            
                                                                                        </p>
                                            
                                                                                        @endif-->





                                            <!-- Outro -->

                                            @foreach ($outroLines as $line)

                                            <p style="{{ $style['paragraph'] }}">

                                                {{ $line }}

                                            </p>

                                            @endforeach



                                            @if(isset($link))

                                            <p style="{{ $style['paragraph'] }}">
                                                @if(isset($link))
                                                <a href="{{ $link }}"><b>Click here</b></a> to download a PDF of the TLV Storage Proposal
                                                @endif
                                            </p>

                                            @endif

                                        </td>

                                    </tr>

                                </table>

                            </td>

                        </tr>



                        <!-- Footer -->

                        <tr>

                            <td>

                                <table style="{{ $style['email-footer'] }}" align="center" width="570" cellpadding="0" cellspacing="0">

                                    <tr>

                                        <td style="{{ $fontFamily }} {{ $style['email-footer_cell'] }}">

                                            <p style="{{ $style['paragraph-sub'] }}">

                                                &copy; {{ date('Y') }}

                                                <a style="{{ $style['anchor'] }} color: #1a5632;" href="thelocalvault.com">{{ config('app.name') }}</a>.

                                                All rights reserved.

                                            </p>

                                        </td>

                                    </tr>

                                </table>

                            </td>

                        </tr>

                    </table>

                </td>

            </tr>

        </table>

    </body>

</html>