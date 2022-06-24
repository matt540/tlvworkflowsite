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

        'body'                        => 'margin: 0; padding: 0; width: 100%;font-family: "Lato", sans-serif;',
        'email-wrapper'               => 'width: 100%; margin: 0; padding: 0;',
        /* Masthead ----------------------- */
        'email-masthead'              => 'padding: 0px 0 0; text-align: center;',
        'email-masthead-logo'         => 'padding: 0 0 20px; text-align: center;',
        'email-masthead_name'         => 'font-size: 16px; font-weight: bold; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white; display: inline-block; vertical-align: top; margin: 17px 20px 0 0',
        'email-body'                  => 'width: 100%; margin: 0; padding: 0; background-color: #FFF;',
        'email-body_inner'            => 'width: auto; margin: 30px auto; padding: 0;',
        'email-body_cell'             => 'padding: 15px 20px;',
        'email-footer'                => 'width: auto; max-width: 570px; margin: 0 auto; padding: 0; text-align: center;',
        'email-footer_cell'           => 'color: #AEAEAE; padding: 0px; text-align: center;',
        /* Body ------------------------------ */
        'body_action'                 => 'width: 100%; margin: 20px auto; padding: 0; text-align: center; background: #fff;',
        'body_sub'                    => 'margin-top: 25px; padding-top: 25px;',
        'body_box'                    => 'padding: 10px;font-family: "Lato", sans-serif;font-size: 14px; color:black; text-align: left;',
        'body_Box_Last'               => ' padding: 15px;font-family: "Lato", sans-serif;font-size: 14px; color:black; text-align: center;',
        /* Type ------------------------------ */
        'anchor'                      => 'color: #3869D4;',
        'header-1'                    => 'margin-top: 0; color: rgb(223, 132, 98); font-family: "Lato", sans-serif;font-size: 24px; font-weight: 700; text-align: left;',
        'header-2_old'                => 'margin-top: 0; color: #74787E; font-family: "Lato", sans-serif;font-size: 24px; font-weight: 700; text-align: left;',
        'header-2'                    => 'margin-top: 0; color: black; font-family: "Lato", sans-serif;font-size: 24px; font-weight: 700; text-align: left;',
        'paragraph_old'               => 'margin-top: 0; color: #74787E; font-size: 14px; line-height: 1.5em;font-family: "Lato", sans-serif; ',
        'paragraph'                   => 'margin-top: 0; color: black; font-size: 14px; line-height: 1.5em;font-family: "Lato", sans-serif; ',
        'paragraph_header'            => 'margin-top: 0; color: #1a5632; font-size: 18px; line-height: 1.5em; font-family: "Lato",sans-serif; font-weight: 500; text-align: center;',
        'paragraph_content'           => 'margin-top: 0; color: black; font-size: 14px; line-height: 1.5em; font-family: "Lato",sans-serif;text-align: center;',
        'paragraph-blue'              => 'margin-top: 0; color: #4285F4; font-size: 14px; line-height: 1.5em;font-family: "Lato", sans-serif; ',
        'paragraph-sub_old'           => 'margin-top: 12px; color: #74787E; font-size: 12px; line-height: 1.5em;',
        'paragraph-sub'               => 'margin-top: 12px; color: black; font-size: 12px; line-height: 1.5em;',
        'paragraph-center'            => 'text-align: center;',
        'paragraph_content_li:before' => "content: '✓';position: absolute;top: 0;left: -16px;transform: rotate(10deg);",
        'paragraph_content_li'        => 'margin-top: 0; color: black; font-size: 14px; line-height: 1.5em;font-family: "Lato", sans-serif;position: relative;',
        /* Buttons ------------------------------ */
        'button'                      => 'display: block; display: inline-block; width: 200px; min-height: 20px; padding: 10px;
                 background-color: #3869D4; border-radius: 3px; color: #ffffff; font-size: 15px; line-height: 25px;
                 text-align: center; text-decoration: none; -webkit-text-size-adjust: none;',
        'button--green'               => 'background-color: #22BC66;',
        'button--red'                 => 'background-color: #dc4d2f;',
        'button--blue'                => 'background-color: #3869D4; ',
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
                                            <p>

                                                @if (! empty($line1))
                                            <p style="{{ $style['paragraph_header'] }}">
                                                {{ $line1 }}
                                            </p>
                                            @endif
                                            @if (! empty($line4))
                                            <p style="{{ $style['paragraph_content'] }}">
                                                <i>{{ $line4 }}</i>
                                            </p>
                                            @endif
                                            @if (! empty($line5))
                                                <p style="{{ $style['paragraph_content'] }}">
                                                    <i>{{ $line5 }}</i>
                                                </p>

                                            @if (! empty($line2))
                                            <p style="{{ $style['paragraph_header'] }}">
                                                {{ $line2 }}
                                            </p>
                                            @endif
                                            @foreach ($introLines as $line)
                                            <p style="{{ $style['paragraph_content'] }}">
                                                {{ $line }}
                                            </p>
                                            @endforeach

                                            <div style="width:100%;">
                                                <div style="float: left;width: 70%;">
                                                    <ul style="margin-left: 30px;">
                                                        @foreach ($outroLines as $line)
                                                        <li style="{{ $style['paragraph_content_li'] }}">
                                                            {{ $line }}
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                <div style="float: right;width: 30%;">
                                                    <img style="width: auto;height: 300px;" src="{{asset('public/assets/images/baby_doll_product.png')}}" alt="" />
                                                </div>
                                            </div>
                                            <div style="width:100%;display: inline-block;">
                                            @if (! empty($line3))
                                            <p style="{{ $style['paragraph_header'] }}">
                                                <i>{{ $line3 }}</i>
                                            </p>
                                            @endif
                                            </div>

                                            @endif
                                            <!-- Salutation -->
                                            <p style="{{ $style['paragraph'] }}">
                                                Cheers,<br>{{ config('app.name') }} Team
                                            </p>

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
                                                <a style="{{ $style['anchor'] }}; color: #1a5632;" href="thelocalvault.com" target="_blank">{{ config('app.name') }}</a>.
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
