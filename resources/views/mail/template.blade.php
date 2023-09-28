<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title style="text-transform: capitalize;">{{ $subject }}</title>

    <style type="text/css">
        .reservation-div {
            margin-top: 30px;
            justify-content: center;
            display: flex;
            text-decoration: none;
        }

        .reservation-btn {
            color: #ffffff !important;
            background: #d82b2b;
            font-weight: bold;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 500ms;

            transform: translateX(var(--tw-translate-x)) translateY(var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            padding-left: 1.75rem;
            padding-right: 1.75rem;
            border-radius: 0.375rem;
            font-weight: 700;
            font-size: 1rem;
            line-height: 1.5rem;
            align-items: center;
            text-decoration: none !important;
        }

        p {
            margin: 10px 0;
            padding: 0;
        }

        table {
            border-collapse: collapse;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            display: block;
            margin: 0;
            padding: 0;
        }

        img,
        a img {
            border: 0;
            height: auto;
            outline: none;
            text-decoration: none;
        }

        body,
        #bodyTable,
        #bodyCell {
            height: 100%;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .mcnPreviewText {
            display: none !important;
        }

        #outlook a {
            padding: 0;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        table {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        .ReadMsgBody {
            width: 100%;
        }

        .ExternalClass {
            width: 100%;
        }

        p,
        a,
        li,
        td,
        blockquote {
            mso-line-height-rule: exactly;
        }

        a[href^=tel],
        a[href^=sms] {
            color: inherit;
            cursor: default;
            text-decoration: none;
        }

        p,
        a,
        li,
        td,
        body,
        table,
        blockquote {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass td,
        .ExternalClass div,
        .ExternalClass span,
        .ExternalClass font {
            line-height: 100%;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        .templateContainer {
            max-width: 600px !important;
        }

        a.mcnButton {
            display: block;
        }

        .mcnImage,
        .mcnRetinaImage {
            vertical-align: bottom;
        }

        .mcnTextContent {
            word-break: break-word;
        }

        .mcnTextContent img {
            height: auto !important;
        }

        .mcnDividerBlock {
            table-layout: fixed !important;
        }



        body,
        #bodyTable {
            background-color: #FAFAFA;
        }


        #bodyCell {
            border-top: 0;
        }


        h1 {
            color: #202020;
            font-family: Helvetica;
            font-size: 26px;
            font-style: normal;
            font-weight: bold;
            line-height: 125%;
            letter-spacing: normal;
            text-align: left;
        }



        h2 {
            color: #202020;
            font-family: Helvetica;
            font-size: 22px;
            font-style: normal;
            font-weight: bold;
            line-height: 125%;
            letter-spacing: normal;
            text-align: left;
        }



        h3 {
            color: #202020;
            font-family: Helvetica;
            font-size: 20px;
            font-style: normal;
            font-weight: bold;
            line-height: 125%;
            letter-spacing: normal;
            text-align: left;
        }



        h4 {
            color: #202020;
            font-family: Helvetica;
            font-size: 18px;
            font-style: normal;
            font-weight: bold;
            line-height: 125%;
            letter-spacing: normal;
            text-align: left;
        }


        #templatePreheader {
            background-color: #FAFAFA;
            background-image: none;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            border-top: 0;
            border-bottom: 0;
            padding-top: 9px;
            padding-bottom: 9px;
        }


        #templatePreheader .mcnTextContent,
        #templatePreheader .mcnTextContent p {
            color: #656565;
            font-family: Helvetica;
            font-size: 12px;
            line-height: 150%;
            text-align: left;
        }



        #templatePreheader .mcnTextContent a,
        #templatePreheader .mcnTextContent p a {
            color: #656565;
            font-weight: normal;
            text-decoration: underline;
        }

        #templateHeader {
            background-color: #FFFFFF;
            background-image: none;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            border-top: 0;
            border-bottom: 0;
            padding-top: 9px;
            padding-bottom: 0;
        }


        #templateHeader .mcnTextContent,
        #templateHeader .mcnTextContent p {
            color: #202020;
            font-family: Helvetica;
            font-size: 16px;
            line-height: 150%;
            text-align: left;
        }


        #templateHeader .mcnTextContent a,
        #templateHeader .mcnTextContent p a {
            color: #007C89;
            font-weight: normal;
            text-decoration: underline;
        }


        #templateBody {
            background-color: #FFFFFF;
            background-image: none;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            border-top: 0;
            border-bottom: 0;
            padding-top: 9px;
            padding-bottom: 9px;
        }


        #templateBody .mcnTextContent,
        #templateBody .mcnTextContent p {
            color: #202020;
            font-family: Helvetica;
            font-size: 16px;
            line-height: 150%;
            text-align: left;
        }



        #templateBody .mcnTextContent a,
        #templateBody .mcnTextContent p a {
            color: #007C89;
            font-weight: normal;
            text-decoration: underline;
        }


        #templateFooter {
            background-color: #FAFAFA;
            background-image: none;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            border-top: 0;
            border-bottom: 0;
            padding-top: 9px;
            padding-bottom: 9px;
        }


        #templateFooter .mcnTextContent,
        #templateFooter .mcnTextContent p {
            color: #656565;
            font-family: Helvetica;
            font-size: 12px;
            line-height: 150%;
            text-align: center;
        }



        #templateFooter .mcnTextContent a,
        #templateFooter .mcnTextContent p a {
            color: #656565;
            font-weight: normal;
            text-decoration: underline;
        }

        @media only screen and (min-width:768px) {
            .templateContainer {
                width: 600px !important;
            }
        }

        @media only screen and (max-width: 480px) {

            body,
            table,
            td,
            p,
            a,
            li,
            blockquote {
                -webkit-text-size-adjust: none !important;
            }
        }

        @media only screen and (max-width: 480px) {
            body {
                width: 100% !important;
                min-width: 100% !important;
            }
        }

        @media only screen and (max-width: 480px) {
            .mcnRetinaImage {
                max-width: 100% !important;
            }
        }

        @media only screen and (max-width: 480px) {
            .mcnImage {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 480px) {

            .mcnCartContainer,
            .mcnCaptionTopContent,
            .mcnRecContentContainer,
            .mcnCaptionBottomContent,
            .mcnTextContentContainer,
            .mcnBoxedTextContentContainer,
            .mcnImageGroupContentContainer,
            .mcnCaptionLeftTextContentContainer,
            .mcnCaptionRightTextContentContainer,
            .mcnCaptionLeftImageContentContainer,
            .mcnCaptionRightImageContentContainer,
            .mcnImageCardLeftTextContentContainer,
            .mcnImageCardRightTextContentContainer,
            .mcnImageCardLeftImageContentContainer,
            .mcnImageCardRightImageContentContainer {
                max-width: 100% !important;
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 480px) {
            .mcnBoxedTextContentContainer {
                min-width: 100% !important;
            }
        }

        @media only screen and (max-width: 480px) {
            .mcnImageGroupContent {
                padding: 9px !important;
            }
        }

        @media only screen and (max-width: 480px) {

            .mcnCaptionLeftContentOuter .mcnTextContent,
            .mcnCaptionRightContentOuter .mcnTextContent {
                padding-top: 9px !important;
            }
        }

        @media only screen and (max-width: 480px) {

            .mcnImageCardTopImageContent,
            .mcnCaptionBottomContent:last-child .mcnCaptionBottomImageContent,
            .mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent {
                padding-top: 18px !important;
            }
        }

        @media only screen and (max-width: 480px) {
            .mcnImageCardBottomImageContent {
                padding-bottom: 9px !important;
            }
        }

        @media only screen and (max-width: 480px) {
            .mcnImageGroupBlockInner {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }
        }

        @media only screen and (max-width: 480px) {
            .mcnImageGroupBlockOuter {
                padding-top: 9px !important;
                padding-bottom: 9px !important;
            }
        }

        @media only screen and (max-width: 480px) {

            .mcnTextContent,
            .mcnBoxedTextContentColumn {
                padding-right: 18px !important;
                padding-left: 18px !important;
            }
        }

        @media only screen and (max-width: 480px) {

            .mcnImageCardLeftImageContent,
            .mcnImageCardRightImageContent {
                padding-right: 18px !important;
                padding-bottom: 0 !important;
                padding-left: 18px !important;
            }
        }

        @media only screen and (max-width: 480px) {
            .mcpreview-image-uploader {
                display: none !important;
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 480px) {


            h1 {
                font-size: 22px !important;
                line-height: 125% !important;
            }
        }

        @media only screen and (max-width: 480px) {

            h2 {
                font-size: 20px !important;
                line-height: 125% !important;
            }
        }

        @media only screen and (max-width: 480px) {


            h3 {
                font-size: 18px !important;
                line-height: 125% !important;
            }
        }

        @media only screen and (max-width: 480px) {

            h4 {
                font-size: 16px !important;
                line-height: 150% !important;
            }
        }

        @media only screen and (max-width: 480px) {

            .mcnBoxedTextContentContainer .mcnTextContent,
            .mcnBoxedTextContentContainer .mcnTextContent p {
                font-size: 14px !important;
                line-height: 150% !important;
            }
        }

        @media only screen and (max-width: 480px) {

            #templatePreheader {
                display: block !important;
            }
        }

        @media only screen and (max-width: 480px) {

            #templatePreheader .mcnTextContent,
            #templatePreheader .mcnTextContent p {
                font-size: 14px !important;
                line-height: 150% !important;
            }
        }

        @media only screen and (max-width: 480px) {

            #templateHeader .mcnTextContent,
            #templateHeader .mcnTextContent p {
                font-size: 16px !important;
                line-height: 150% !important;
            }
        }

        @media only screen and (max-width: 480px) {

            #templateBody .mcnTextContent,
            #templateBody .mcnTextContent p {
                font-size: 16px !important;
                line-height: 150% !important;
            }
        }

        @media only screen and (max-width: 480px) {

            #templateFooter .mcnTextContent,
            #templateFooter .mcnTextContent p {
                font-size: 14px !important;
                line-height: 150% !important;
            }
        }

    </style>
</head>

<body>
    {{-- <span class="mcnPreviewText"
        style="display:none; font-size:0px; line-height:0px; max-height:0px; max-width:0px; opacity:0; overflow:hidden; visibility:hidden; mso-hide:all;"></span> --}}

    <center>
        <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
            <tr>
                <td align="center" valign="top" id="bodyCell">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td align="center" valign="top" id="templateHeader">

                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                    class="templateContainer">
                                    <tr>
                                        <td valign="top" class="headerContainer">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                class="mcnImageBlock" style="min-width:100%;">
                                                <tbody class="mcnImageBlockOuter">
                                                    <tr>
                                                        <td valign="top" style="padding:9px" class="mcnImageBlockInner">
                                                            <table align="left" width="100%" border="0" cellpadding="0"
                                                                cellspacing="0" class="mcnImageContentContainer"
                                                                style="min-width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="mcnImageContent" valign="top"
                                                                            style="padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;">
                                                                            @if ($banner)
                                                                                <img align="center" alt=""
                                                                                    src="{{ $banner }}"
                                                                                    style="height:190px; width:auto; max-width:1500px; padding-bottom: 0; display: inline !important; vertical-align: bottom;"
                                                                                    class="mcnImage">
                                                                            @else
                                                                                {{-- <img align="center" alt=""
src="https://mcusercontent.com/0dcf4e6612a93def9b158f53a/images/5bbeb18d-c534-4968-a7c6-be72ee61420a.png"
width="564"
style="max-width:1500px; padding-bottom: 0; display: inline !important; vertical-align: bottom;"
class="mcnImage"> --}}
                                                                            @endif




                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" id="templateBody">

                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                    class="templateContainer">
                                    <tr>
                                        <td valign="top" class="bodyContainer">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                class="mcnTextBlock" style="min-width:100%;">
                                                <tbody class="mcnTextBlockOuter">
                                                    <tr>
                                                        <td valign="top" class="mcnTextBlockInner"
                                                            style="padding-top:9px;">
                                                            <table align="left" border="0" cellpadding="0"
                                                                cellspacing="0" style="max-width:100%; min-width:100%;"
                                                                width="100%" class="mcnTextContentContainer">
                                                                <tbody>
                                                                    <tr>

                                                                        <td valign="top" class="mcnTextContent"
                                                                            style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">

                                                                            <p
                                                                                style="margin-bottom: 15px; text-transform:capitalize">
                                                                                Hello
                                                                                {{ $name }}</p>

                                                                            <h1 style="text-align: center;">
                                                                                {{ $subject }}</h1>

                                                                            {!! $msg !!}
                                                                            {{-- <p>Thank you for using our application!</p>
                                                                            <p>Regards,</p>
                                                                            <p>{{ config('app.name') }}</p> --}}

                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                class="mcnShareBlock" style="min-width:100%;">
                                                <tbody class="mcnShareBlockOuter">
                                                    <tr>
                                                        <td valign="top" style="padding:9px" class="mcnShareBlockInner">
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                width="100%" class="mcnShareContentContainer"
                                                                style="min-width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="center"
                                                                            style="padding-top:0; padding-left:9px; padding-bottom:0; padding-right:9px;">
                                                                            <table align="center" border="0"
                                                                                cellpadding="0" cellspacing="0"
                                                                                width="100%" style="min-width:100%;"
                                                                                class="mcnShareContent">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" valign="top"
                                                                                            class="mcnShareContentItemContainer"
                                                                                            style="padding-top:9px; padding-right:9px; padding-left:9px;">
                                                                                            <table align="center"
                                                                                                border="0"
                                                                                                cellpadding="0"
                                                                                                cellspacing="0">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="left"
                                                                                                            valign="top">

                                                                                                            {{-- <table
                                                                                                                align="left"
                                                                                                                border="0"
                                                                                                                cellpadding="0"
                                                                                                                cellspacing="0">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td valign="top"
                                                                                                                            style="padding-right:9px; padding-bottom:9px;"
                                                                                                                            class="mcnShareContentItemContainer">
                                                                                                                            <table
                                                                                                                                border="0"
                                                                                                                                cellpadding="0"
                                                                                                                                cellspacing="0"
                                                                                                                                width=""
                                                                                                                                class="mcnShareContentItem"
                                                                                                                                style="border-collapse:separate;">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="left"
                                                                                                                                            valign="middle"
                                                                                                                                            style="padding-top:5px; padding-right:9px; padding-bottom:5px; padding-left:9px;">
                                                                                                                                            <table
                                                                                                                                                align="left"
                                                                                                                                                border="0"
                                                                                                                                                cellpadding="0"
                                                                                                                                                cellspacing="0"
                                                                                                                                                width="">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center"
                                                                                                                                                            valign="middle"
                                                                                                                                                            width="24"
                                                                                                                                                            class="mcnShareIconContent">
                                                                                                                                                            <a href="http://www.facebook.com/sharer/sharer.php?u=*|URL:ARCHIVE_LINK_SHORT|*"
                                                                                                                                                                target="_blank"><img
                                                                                                                                                                    src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-dark-facebook-48.png"
                                                                                                                                                                    alt="Share"
                                                                                                                                                                    style="display:block;"
                                                                                                                                                                    height="24"
                                                                                                                                                                    width="24"
                                                                                                                                                                    class=""></a>
                                                                                                                                                        </td>
                                                                                                                                                        <td align="left"
                                                                                                                                                            valign="middle"
                                                                                                                                                            class="mcnShareTextContent"
                                                                                                                                                            style="padding-left:5px;">
                                                                                                                                                            <a href="http://www.facebook.com/sharer/sharer.php?u=*|URL:ARCHIVE_LINK_SHORT|*"
                                                                                                                                                                target=""
                                                                                                                                                                style="color: #202020;font-family: Arial;font-size: 12px;font-weight: normal;line-height: normal;text-align: center;text-decoration: none;">Share</a>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>

                                                                                                            <table
                                                                                                                align="left"
                                                                                                                border="0"
                                                                                                                cellpadding="0"
                                                                                                                cellspacing="0">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td valign="top"
                                                                                                                            style="padding-right:9px; padding-bottom:9px;"
                                                                                                                            class="mcnShareContentItemContainer">
                                                                                                                            <table
                                                                                                                                border="0"
                                                                                                                                cellpadding="0"
                                                                                                                                cellspacing="0"
                                                                                                                                width=""
                                                                                                                                class="mcnShareContentItem"
                                                                                                                                style="border-collapse:separate;">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="left"
                                                                                                                                            valign="middle"
                                                                                                                                            style="padding-top:5px; padding-right:9px; padding-bottom:5px; padding-left:9px;">
                                                                                                                                            <table
                                                                                                                                                align="left"
                                                                                                                                                border="0"
                                                                                                                                                cellpadding="0"
                                                                                                                                                cellspacing="0"
                                                                                                                                                width="">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center"
                                                                                                                                                            valign="middle"
                                                                                                                                                            width="24"
                                                                                                                                                            class="mcnShareIconContent">
                                                                                                                                                            <a href="http://twitter.com/intent/tweet?text=*|URL:MC_SUBJECT|*: *|URL:ARCHIVE_LINK_SHORT|*"
                                                                                                                                                                target="_blank"><img
                                                                                                                                                                    src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-dark-twitter-48.png"
                                                                                                                                                                    alt="Tweet"
                                                                                                                                                                    style="display:block;"
                                                                                                                                                                    height="24"
                                                                                                                                                                    width="24"
                                                                                                                                                                    class=""></a>
                                                                                                                                                        </td>
                                                                                                                                                        <td align="left"
                                                                                                                                                            valign="middle"
                                                                                                                                                            class="mcnShareTextContent"
                                                                                                                                                            style="padding-left:5px;">
                                                                                                                                                            <a href="http://twitter.com/intent/tweet?text=*|URL:MC_SUBJECT|*: *|URL:ARCHIVE_LINK_SHORT|*"
                                                                                                                                                                target=""
                                                                                                                                                                style="color: #202020;font-family: Arial;font-size: 12px;font-weight: normal;line-height: normal;text-align: center;text-decoration: none;">Tweet</a>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>

                                                                                                            <table
                                                                                                                align="left"
                                                                                                                border="0"
                                                                                                                cellpadding="0"
                                                                                                                cellspacing="0">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td valign="top"
                                                                                                                            style="padding-right:9px; padding-bottom:9px;"
                                                                                                                            class="mcnShareContentItemContainer">
                                                                                                                            <table
                                                                                                                                border="0"
                                                                                                                                cellpadding="0"
                                                                                                                                cellspacing="0"
                                                                                                                                width=""
                                                                                                                                class="mcnShareContentItem"
                                                                                                                                style="border-collapse:separate;">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="left"
                                                                                                                                            valign="middle"
                                                                                                                                            style="padding-top:5px; padding-right:9px; padding-bottom:5px; padding-left:9px;">
                                                                                                                                            <table
                                                                                                                                                align="left"
                                                                                                                                                border="0"
                                                                                                                                                cellpadding="0"
                                                                                                                                                cellspacing="0"
                                                                                                                                                width="">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center"
                                                                                                                                                            valign="middle"
                                                                                                                                                            width="24"
                                                                                                                                                            class="mcnShareIconContent">
                                                                                                                                                            <a href="*|FORWARD|*"
                                                                                                                                                                target="_blank"><img
                                                                                                                                                                    src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-dark-forwardtofriend-48.png"
                                                                                                                                                                    alt="Forward"
                                                                                                                                                                    style="display:block;"
                                                                                                                                                                    height="24"
                                                                                                                                                                    width="24"
                                                                                                                                                                    class=""></a>
                                                                                                                                                        </td>
                                                                                                                                                        <td align="left"
                                                                                                                                                            valign="middle"
                                                                                                                                                            class="mcnShareTextContent"
                                                                                                                                                            style="padding-left:5px;">
                                                                                                                                                            <a href="*|FORWARD|*"
                                                                                                                                                                target=""
                                                                                                                                                                style="color: #202020;font-family: Arial;font-size: 12px;font-weight: normal;line-height: normal;text-align: center;text-decoration: none;">Forward</a>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>

                                                                                                            <table
                                                                                                                align="left"
                                                                                                                border="0"
                                                                                                                cellpadding="0"
                                                                                                                cellspacing="0">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td valign="top"
                                                                                                                            style="padding-right:0; padding-bottom:9px;"
                                                                                                                            class="mcnShareContentItemContainer">
                                                                                                                            <table
                                                                                                                                border="0"
                                                                                                                                cellpadding="0"
                                                                                                                                cellspacing="0"
                                                                                                                                width=""
                                                                                                                                class="mcnShareContentItem"
                                                                                                                                style="border-collapse:separate;">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="left"
                                                                                                                                            valign="middle"
                                                                                                                                            style="padding-top:5px; padding-right:9px; padding-bottom:5px; padding-left:9px;">
                                                                                                                                            <table
                                                                                                                                                align="left"
                                                                                                                                                border="0"
                                                                                                                                                cellpadding="0"
                                                                                                                                                cellspacing="0"
                                                                                                                                                width="">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>
                                                                                                                                                        <td align="center"
                                                                                                                                                            valign="middle"
                                                                                                                                                            width="24"
                                                                                                                                                            class="mcnShareIconContent">
                                                                                                                                                            <a href="http://www.linkedin.com/shareArticle?url=*|URL:ARCHIVE_LINK_SHORT|*&amp;mini=true&amp;title=*|URL:MC_SUBJECT|*"
                                                                                                                                                                target="_blank"><img
                                                                                                                                                                    src="https://cdn-images.mailchimp.com/icons/social-block-v2/outline-dark-linkedin-48.png"
                                                                                                                                                                    alt="Share"
                                                                                                                                                                    style="display:block;"
                                                                                                                                                                    height="24"
                                                                                                                                                                    width="24"
                                                                                                                                                                    class=""></a>
                                                                                                                                                        </td>
                                                                                                                                                        <td align="left"
                                                                                                                                                            valign="middle"
                                                                                                                                                            class="mcnShareTextContent"
                                                                                                                                                            style="padding-left:5px;">
                                                                                                                                                            <a href="http://www.linkedin.com/shareArticle?url=*|URL:ARCHIVE_LINK_SHORT|*&amp;mini=true&amp;title=*|URL:MC_SUBJECT|*"
                                                                                                                                                                target=""
                                                                                                                                                                style="color: #202020;font-family: Arial;font-size: 12px;font-weight: normal;line-height: normal;text-align: center;text-decoration: none;">Share</a>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table> --}}

                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                        <tr>
                            <td align="center" valign="top" id="templateFooter">

                                <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                                    class="templateContainer">
                                    <tr>
                                        <td valign="top" class="footerContainer">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                class="mcnFollowBlock" style="min-width:100%;">
                                                <tbody class="mcnFollowBlockOuter">
                                                    <tr>
                                                        <td align="center" valign="top" style="padding:9px"
                                                            class="mcnFollowBlockInner">
                                                            <table border="0" cellpadding="0" cellspacing="0"
                                                                width="100%" class="mcnFollowContentContainer"
                                                                style="min-width:100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td align="center"
                                                                            style="padding-left:9px;padding-right:9px;">
                                                                            <table border="0" cellpadding="0"
                                                                                cellspacing="0" width="100%"
                                                                                style="min-width:100%;"
                                                                                class="mcnFollowContent">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="center" valign="top"
                                                                                            style="padding-top:9px; padding-right:9px; padding-left:9px;">
                                                                                            <table align="center"
                                                                                                border="0"
                                                                                                cellpadding="0"
                                                                                                cellspacing="0">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                        <td align="center"
                                                                                                            valign="top">


                                                                                                            @if ($vendor->twitter_link)
                                                                                                                <table
                                                                                                                    align="left"
                                                                                                                    border="0"
                                                                                                                    cellpadding="0"
                                                                                                                    cellspacing="0"
                                                                                                                    style="display:inline;">
                                                                                                                    <tbody>
                                                                                                                        <tr>
                                                                                                                            <td valign="top"
                                                                                                                                style="padding-right:10px; padding-bottom:9px;"
                                                                                                                                class="mcnFollowContentItemContainer">
                                                                                                                                <table
                                                                                                                                    border="0"
                                                                                                                                    cellpadding="0"
                                                                                                                                    cellspacing="0"
                                                                                                                                    width="100%"
                                                                                                                                    class="mcnFollowContentItem">
                                                                                                                                    <tbody>
                                                                                                                                        <tr>
                                                                                                                                            <td align="left"
                                                                                                                                                valign="middle"
                                                                                                                                                style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                                                                                                <table
                                                                                                                                                    align="left"
                                                                                                                                                    border="0"
                                                                                                                                                    cellpadding="0"
                                                                                                                                                    cellspacing="0"
                                                                                                                                                    width="">
                                                                                                                                                    <tbody>
                                                                                                                                                        <tr>

                                                                                                                                                            <td align="center"
                                                                                                                                                                valign="middle"
                                                                                                                                                                width="24"
                                                                                                                                                                class="mcnFollowIconContent">
                                                                                                                                                                <a href="{{ $vendor->twitter_link }}"
                                                                                                                                                                    target="_blank"><img
                                                                                                                                                                        src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-twitter-48.png"
                                                                                                                                                                        alt="Twitter"
                                                                                                                                                                        style="display:block;"
                                                                                                                                                                        height="24"
                                                                                                                                                                        width="24"
                                                                                                                                                                        class=""></a>
                                                                                                                                                            </td>


                                                                                                                                                        </tr>
                                                                                                                                                    </tbody>
                                                                                                                                                </table>
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            @endif



                                                                                                            @if ($vendor->facebook_link)
                                                                                                                <table
                                                                                                                    align="
                                                                                                                                                                        left"
                                                                                                                    border="0"
                                                                                                                    cellpadding="0"
                                                                                                                    cellspacing="0"
                                                                                                                    style="display:inline;">
                                                                                                                    <tbody>
                                                                                                                        <tr>
                                                                                                                            <td valign="top"
                                                                                                                                style="padding-right:10px; padding-bottom:9px;"
                                                                                                                                class="mcnFollowContentItemContainer">
                                                                                                                                <table
                                                                                                                                    border="0"
                                                                                                                                    cellpadding="0"
                                                                                                                                    cellspacing="0"
                                                                                                                                    width="100%"
                                                                                                                                    class="mcnFollowContentItem">
                                                                                                                                    <tbody>
                                                                                                                                        <tr>
                                                                                                                                            <td align="left"
                                                                                                                                                valign="middle"
                                                                                                                                                style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                                                                                                <table
                                                                                                                                                    align="left"
                                                                                                                                                    border="0"
                                                                                                                                                    cellpadding="0"
                                                                                                                                                    cellspacing="0"
                                                                                                                                                    width="">
                                                                                                                                                    <tbody>
                                                                                                                                                        <tr>

                                                                                                                                                            <td align="center"
                                                                                                                                                                valign="middle"
                                                                                                                                                                width="24"
                                                                                                                                                                class="mcnFollowIconContent">
                                                                                                                                                                <a href="{{ $vendor->facebook_link }}"
                                                                                                                                                                    target="_blank"><img
                                                                                                                                                                        src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-facebook-48.png"
                                                                                                                                                                        alt="Facebook"
                                                                                                                                                                        style="display:block;"
                                                                                                                                                                        height="24"
                                                                                                                                                                        width="24"
                                                                                                                                                                        class=""></a>
                                                                                                                                                            </td>


                                                                                                                                                        </tr>
                                                                                                                                                    </tbody>
                                                                                                                                                </table>
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            @endif



                                                                                                            <table
                                                                                                                align="
                                                                                                                                                                                                    left"
                                                                                                                border="0"
                                                                                                                cellpadding="0"
                                                                                                                cellspacing="0"
                                                                                                                style="display:inline;">
                                                                                                                <tbody>
                                                                                                                    <tr>
                                                                                                                        <td valign="top"
                                                                                                                            style="padding-right:10px; padding-bottom:9px;"
                                                                                                                            class="mcnFollowContentItemContainer">
                                                                                                                            <table
                                                                                                                                border="0"
                                                                                                                                cellpadding="0"
                                                                                                                                cellspacing="0"
                                                                                                                                width="100%"
                                                                                                                                class="mcnFollowContentItem">
                                                                                                                                <tbody>
                                                                                                                                    <tr>
                                                                                                                                        <td align="left"
                                                                                                                                            valign="middle"
                                                                                                                                            style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                                                                                            <table
                                                                                                                                                align="left"
                                                                                                                                                border="0"
                                                                                                                                                cellpadding="0"
                                                                                                                                                cellspacing="0"
                                                                                                                                                width="">
                                                                                                                                                <tbody>
                                                                                                                                                    <tr>

                                                                                                                                                        <td align="center"
                                                                                                                                                            valign="middle"
                                                                                                                                                            width="24"
                                                                                                                                                            class="mcnFollowIconContent">
                                                                                                                                                            <a href="{{ route('restaurants.index', ['id' => $vendor->id]) }}"
                                                                                                                                                                target="_blank"><img
                                                                                                                                                                    src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-link-48.png"
                                                                                                                                                                    alt="Website"
                                                                                                                                                                    style="display:block;"
                                                                                                                                                                    height="24"
                                                                                                                                                                    width="24"
                                                                                                                                                                    class=""></a>
                                                                                                                                                        </td>


                                                                                                                                                    </tr>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </td>
                                                                                                                                    </tr>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                </tbody>
                                                                                                            </table>



                                                                                                            @if ($vendor->instagram_link)
                                                                                                                <table
                                                                                                                    align="
                                                                                                                                                                                                                                        left"
                                                                                                                    border="0"
                                                                                                                    cellpadding="0"
                                                                                                                    cellspacing="0"
                                                                                                                    style="display:inline;">
                                                                                                                    <tbody>
                                                                                                                        <tr>
                                                                                                                            <td valign="top"
                                                                                                                                style="padding-right:10px; padding-bottom:9px;"
                                                                                                                                class="mcnFollowContentItemContainer">
                                                                                                                                <table
                                                                                                                                    border="0"
                                                                                                                                    cellpadding="0"
                                                                                                                                    cellspacing="0"
                                                                                                                                    width="100%"
                                                                                                                                    class="mcnFollowContentItem">
                                                                                                                                    <tbody>
                                                                                                                                        <tr>
                                                                                                                                            <td align="left"
                                                                                                                                                valign="middle"
                                                                                                                                                style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                                                                                                <table
                                                                                                                                                    align="left"
                                                                                                                                                    border="0"
                                                                                                                                                    cellpadding="0"
                                                                                                                                                    cellspacing="0"
                                                                                                                                                    width="">
                                                                                                                                                    <tbody>
                                                                                                                                                        <tr>

                                                                                                                                                            <td align="center"
                                                                                                                                                                valign="middle"
                                                                                                                                                                width="24"
                                                                                                                                                                class="mcnFollowIconContent">
                                                                                                                                                                <a href="{{ $vendor->instagram_link }}"
                                                                                                                                                                    target="_blank"><img
                                                                                                                                                                        src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-instagram-48.png"
                                                                                                                                                                        alt="Instagram"
                                                                                                                                                                        style="display:block;"
                                                                                                                                                                        height="24"
                                                                                                                                                                        width="24"
                                                                                                                                                                        class=""></a>
                                                                                                                                                            </td>


                                                                                                                                                        </tr>
                                                                                                                                                    </tbody>
                                                                                                                                                </table>
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            @endif


                                                                                                            @if ($vendor->linkedin_link)
                                                                                                                <table
                                                                                                                    align="
                                                                                                                                                                                                                                                                        left"
                                                                                                                    border="0"
                                                                                                                    cellpadding="0"
                                                                                                                    cellspacing="0"
                                                                                                                    style="display:inline;">
                                                                                                                    <tbody>
                                                                                                                        <tr>
                                                                                                                            <td valign="top"
                                                                                                                                style="padding-right:0; padding-bottom:9px;"
                                                                                                                                class="mcnFollowContentItemContainer">
                                                                                                                                <table
                                                                                                                                    border="0"
                                                                                                                                    cellpadding="0"
                                                                                                                                    cellspacing="0"
                                                                                                                                    width="100%"
                                                                                                                                    class="mcnFollowContentItem">
                                                                                                                                    <tbody>
                                                                                                                                        <tr>
                                                                                                                                            <td align="left"
                                                                                                                                                valign="middle"
                                                                                                                                                style="padding-top:5px; padding-right:10px; padding-bottom:5px; padding-left:9px;">
                                                                                                                                                <table
                                                                                                                                                    align="left"
                                                                                                                                                    border="0"
                                                                                                                                                    cellpadding="0"
                                                                                                                                                    cellspacing="0"
                                                                                                                                                    width="">
                                                                                                                                                    <tbody>
                                                                                                                                                        <tr>
    
                                                                                                                                                            <td align="center"
                                                                                                                                                                valign="middle"
                                                                                                                                                                width="24"
                                                                                                                                                                class="mcnFollowIconContent">
                                                                                                                                                                <a href="{{ $vendor->linkedin_link }}"
                                                                                                                                                                    target="_blank"><img
                                                                                                                                                                        src="https://cdn-images.mailchimp.com/icons/social-block-v2/color-linkedin-48.png"
                                                                                                                                                                        alt="LinkedIn"
                                                                                                                                                                        style="display:block;"
                                                                                                                                                                        height="24"
                                                                                                                                                                        width="24"
                                                                                                                                                                        class=""></a>
                                                                                                                                                            </td>
    
    
                                                                                                                                                        </tr>
                                                                                                                                                    </tbody>
                                                                                                                                                </table>
                                                                                                                                            </td>
                                                                                                                                        </tr>
                                                                                                                                    </tbody>
                                                                                                                                </table>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            @endif


                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table
                                                border="
                                                                                                                                                                                                                                                                                                    0"
                                                cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock"
                                                style="min-width:100%;">
                                                <tbody class="mcnDividerBlockOuter">
                                                    <tr>
                                                        <td class="mcnDividerBlockInner"
                                                            style="min-width: 100%; padding: 10px 18px 25px;">
                                                            <table class="mcnDividerContent" border="0" cellpadding="0"
                                                                cellspacing="0" width="100%"
                                                                style="min-width: 100%;border-top: 2px solid #EEEEEE;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <span></span>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                class="mcnTextBlock" style="min-width:100%;">
                                                <tbody class="mcnTextBlockOuter">
                                                    <tr>
                                                        <td valign="top" class="mcnTextBlockInner"
                                                            style="padding-top:9px;">

                                                            <table align="left" border="0" cellpadding="0"
                                                                cellspacing="0" style="max-width:100%; min-width:100%;"
                                                                width="100%" class="mcnTextContentContainer">
                                                                <tbody>
                                                                    <tr>

                                                                        <td valign="top" class="mcnTextContent"
                                                                            style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                                                                            @if ($vendor->address)
                                                                                <strong>Our Adress:</strong>
                                                                                <br>
                                                                                {{ $vendor->address }}
                                                                                <br>
                                                                            @endif
                                                                            <br>
                                                                            <br>
                                                                            <br>
                                                                            <br>
                                                                            @if ($vendor->company_email || $vendor->company_phone)
                                                                                <strong>Contact Us:</strong>
                                                                                <br>
                                                                                <a
                                                                                    href="mailto:{{ $vendor->company_email }}">{{ $vendor->company_email }}</a>
                                                                                , <a
                                                                                    href="tel:{{ $vendor->company_phone }}">{{ $vendor->company_phone }}</a>
                                                                                <br>
                                                                            @endif

                                                                            <strong>Powered by <a
                                                                                    href="http://dinesurf.com/"
                                                                                    target="_blank">Dinesurf</a>
                                                                            </strong>
                                                                            <br>
                                                                            <em>Copyright
                                                                                ©
                                                                                {{ date('Y') }}
                                                                                {{ config('app.name') }},
                                                                                All
                                                                                rights
                                                                                reserved.</em>
                                                                            <br>
                                                                            <br>
                                                                            <br>
                                                                            <br>
                                                                            {{-- <br> Want to change how you receive these
                                                                            emails?<br> You can <a
                                                                                href="*|UPDATE_PROFILE|*">update your
                                                                                preferences</a> or <a
                                                                                href="*|UNSUB|*">unsubscribe from this
                                                                                list</a>.
                                                                            <br> --}}

                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>
