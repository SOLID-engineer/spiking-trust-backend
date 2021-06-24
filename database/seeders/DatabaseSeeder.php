<?php

namespace Database\Seeders;

use App\Models\MailTemplate;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        \App\Models\Company::factory(100)->create();
        \App\Models\Review::factory(1000)->create();

        // Mail templates
        $model = new MailTemplate();
        $model->name = 'For non-purchase experiences';
        $model->subject = '⭐ ⭐ ⭐ ⭐ ⭐  How many stars would you give [CompanyIdentifier]?';
        $model->content = <<<HTML
<!DOCTYPE html><html><head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<title>Your opinion matters
</title>
	<style media="all" type="text/css">
#outlook a {
  padding: 0; }

body {
  width: 100% !important; }

body {
  -webkit-text-size-adjust: none; }

body {
  margin: 0;
  padding: 0; }

img {
  border: 0;
  height: auto;
  line-height: 100%;
  outline: none;
  text-decoration: none; }

table td {
  border-collapse: collapse; }

#backgroundTable {
  height: 100% !important;
  margin: 0;
  width: 100% !important; }

h1,
.h1 {
  color: #202020;
  display: block;
  font-family: Arial;
  font-size: 34px;
  font-weight: bold;
  line-height: 100%;
  margin-top: 0;
  margin-right: 0;
  margin-bottom: 10px;
  margin-left: 0;
  text-align: left; }

h2,
.h2 {
  color: #202020;
  display: block;
  font-family: Arial;
  font-size: 30px;
  font-weight: bold;
  line-height: 100%;
  margin-top: 0;
  margin-right: 0;
  margin-bottom: 10px;
  margin-left: 0;
  text-align: left; }

h3,
.h3 {
  color: #202020;
  display: block;
  font-family: Arial;
  font-size: 26px;
  font-weight: bold;
  line-height: 100%;
  margin-top: 0;
  margin-right: 0;
  margin-bottom: 10px;
  margin-left: 0;
  text-align: left; }

h4,
.h4 {
  color: #202020;
  display: block;
  font-family: Arial;
  font-size: 22px;
  font-weight: bold;
  line-height: 100%;
  margin-top: 0;
  margin-right: 0;
  margin-bottom: 10px;
  margin-left: 0;
  text-align: left; }

#templateContainer,
#templateContainerTd {
  background-color: #FFF; }

tbody div {
  color: #505050;
  font-family: Times New Roman;
  font-size: 14px;
  line-height: 150%;
  text-align: left; }

tbody div a:link,
tbody div a:visited,
tbody div a .yshortcuts {
  color: #336699;
  font-weight: normal;
  text-decoration: underline; }

tbody img {
  display: inline;
  height: auto; }

@media only screen and (max-device-width: 480px) {
  #backgroundTableTd {
    padding: 5px !important; }
  #templateContainerTd {
    padding: 15px !important; }
  h1 {
    font-size: 28px !important; }
  h2 {
    font-size: 24px !important; }
  h3 {
    font-size: 20px !important; }
  h4 {
    font-size: 16px !important; }
  tbody div {
    font-size: 16px !important; }
  table[class="table"],
  td[class="cell"] {
    width: 300px !important; } }

.unsubscribe-link {
  color: #336699;
  text-decoration: underline; }

</style>
	<style media="all" type="text/css">
#templateContainer {
  width: 100%; }

body,
#backgroundTable {
  background-color: #fff; }

.trustpilot-logo {
  padding-bottom: 1rem; }
  .trustpilot-logo img {
    height: 10px; }

.stars {
  width: 100%;
  margin-top: 2em; }

a {
  color: #0c59f2; }

.stars .tp-link {
  font-weight: normal;
  line-height: 1em;
  text-decoration: underline;
  font-size: 18px;
  color: #336699;
  color: #000032;
  color: #0c59f2; }

.stars a {
  color: inherit !important;
  color: black; }

.stars .star-rating {
  text-decoration: none; }

.stars .star-rating .star-rating__img {
  height: 36px;
  vertical-align: middle; }

.stars .radio-btn {
  font-size: 32px;
  color: #ccc;
  vertical-align: middle;
  margin-right: 0.5rem; }

.stars .rating-word {
  display: inline-block;
  line-height: 36px;
  vertical-align: middle;
  height: 36px; }

.star-rating__table {
  margin: .5em 0; }

.star-rating__table td {
  color: inherit;
  font-size: 14px; }

.star-rating__table__rating-word {
  padding-left: .5em; }

.unsubscribe-section {
  color: #999;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 12px;
  margin-top: .5rem; }

.unsubscribe-link {
  color: #336699;
  color: inherit; }

.section__legalnotice {
  color: #999;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 12px;
  table-layout: fixed;
  width: 100%;
  max-width: 700px;
  border-spacing: 0; }

.section__legalnotice__biz-name-address {
  vertical-align: top;
  padding-bottom: 8px; }

.section__legalnotice__biz-details {
  padding-bottom: 8px; }

.section__legalnotice__biz-details__wrap {
  display: inline-block;
  word-wrap: none;
  text-align: left; }

hr {
  color: #ddd;
  background: #ddd;
  border: none;
  height: 1px; }

@media only screen and (max-width: 480px) {
  .unsubscribe-section {
    font-size: 12px !important; } }

@media only screen and (min-width: 481px) {
  #templateContainer {
    max-width: 700px; }
  .trustpilot-logo img {
    height: auto !important;
    max-width: 120px !important; }
  .stars {
    margin-bottom: 1rem; }
  .stars .tp-link {
    font-size: inherit !important; } }

/**
 * Helper class
 */
.align-center {
  text-align: center; }

/**
 * Media queries
 */
@media only screen and (max-width: 360px) {
  .rating-word {
    display: none; } }

</style>
	<style media="all" type="text/css">
/**
 * Targets Gmail and iOS Mail when they add links around contact info
 **/
a[x-apple-data-detectors] {
  color: inherit !important;
  text-decoration: none !important;
  font-size: inherit !important;
  font-family: inherit !important;
  font-weight: inherit !important;
  line-height: inherit !important; }

u + #body a {
  color: inherit;
  font-size: inherit;
  font-family: inherit;
  font-weight: inherit;
  line-height: inherit; }

.ii a[href] {
  color: inherit;
  text-decoration: underline; }

/**
 * End of Gmail and iOS Mail targeting
 */

</style>
	<style>
		.section__legalnotice__two-split {
			min-width: 200% !important;
		}
		       
	</style>
</head>
<body id="body" style="width: 100% !important; -webkit-text-size-adjust: none; margin: 0; padding: 0; background-color: #fff;">
<table border="0" cellpadding="0" cellspacing="0" id="backgroundTable" style="background-color: #fff; width: 100% !important; height: 100% !important; margin: 0;" width="100% !important" height="100% !important">
	<tbody>
		<tr>
			<td style="border-collapse: collapse;">
			 <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="700">
            <tr>
            <td align="center" valign="top" width="700">
            <![endif]-->
			<table align="center" border="0" cellpadding="0" cellspacing="0" class="table" id="templateContainer" style="background-color: #FFF; width: 100%;" width="100%">
				<tbody>
					<tr>
						<td style="border-collapse: collapse;">
						<table border="0" cellpadding="0" cellspacing="0" class="table" id="templateBody">
							<tbody>
								<tr>
									<td style="border-collapse: collapse;">
									
									<div style="color: #505050; font-family: Times New Roman; font-size: 14px; line-height: 150%; text-align: left;"><br>
									<strong>Dear [Name],
</strong><br>
									<br>
									Thank you for choosing [CompanyIdentifier]. <br>
									
									<div class="stars" style="color: #505050; font-family: Times New Roman; font-size: 14px; line-height: 150%; text-align: left; width: 100%; margin-top: 2em;">
										<h3 style="color: #202020; display: block; font-family: Arial; font-size: 26px; font-weight: bold; line-height: 100%; margin-top: 0; margin-right: 0; margin-bottom: 10px; margin-left: 0; text-align: left;"><a class="tp-link" href="[Link]" style="color: #0c59f2; font-weight: normal; line-height: 1em; text-decoration: underline; font-size: 18px;">How did we do?
</a></h3>
										

										[Stars]


									</div>
									<br>
									Your experience is important to us and your review (whether good, bad or otherwise) will be posted on Trustpilot.com immediately to help other people make more informed decisions.<br> <br> <strong>Thanks for your time, <br> [CompanyIdentifier]</strong>

									<br>
									<br>
									

									

									<strong>Please note:</strong>  This email is sent automatically, so you may have received this review invitation before the arrival of your package or service. In this case, you are welcome to wait with writing your review until your package or service arrives. &nbsp;
<br>
									<br>

									[LegalNotice]
								    
									</div></td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
				</tbody>
			</table>
			<!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
			</td>
		</tr>
	</tbody>
</table>

</body></html>
HTML;
        $model->type = MailTemplate::TYPE_SERVICE_REVIEW_INVITATION;
        $model->is_default = true;
        $model->save();


    }
}
