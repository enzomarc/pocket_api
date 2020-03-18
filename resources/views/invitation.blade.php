<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pocket Invitation</title>

    <style>
        body {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            font-family: "GT America", "HelveticaNeue", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: .9em; /* currently ems cause chrome bug misinterpreting rems on body element */
            line-height: 1.6;
            font-weight: 100;
            color: #333;
        }

        p {
            margin: 0;
        }

        a {
            color: #0c81e7;
            text-decoration: none;}
        a:hover {
            color: #0FA0CE; }

        hr {
            border: solid .5px #ddd;
            width: 100%;
            margin-bottom: 5%;
        }

        a.btn {
            color: #fff;
            background-color: #3BB75E;
            padding: 10px 5px;
            margin-top: 15px;
            border-radius: 3px;
            width: 140px;
            text-align: center;
            font-size: .9em;
        }

        .row {
            display: flex;
            flex-direction: column;
        }

        .logo_container {
            margin-top: 1%;
        }

        .logo_container img {
            width: 128px;
        }

        .mail_container {
            border: solid 1px #ddd;
            border-radius: 3px;
            padding: 60px 30px;
            margin-top: 2%;
            width: 25%;
        }

        .footer {
            font-size: .8em;
            margin-top: 2%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="row logo_container">
        <img src="logo.png" alt="logo"> <!--  -->
    </div>

    <div class="row mail_container">
        <b style="font-weight: 400">Hi!</b>
        <p>{{ $invitation->by->first_name . ' ' . $invitation->by->last_name }} has just added you to {{ $invitation->shop->business_name }} team on Pocket.</p>
        <br>
        <p>We've added {{ $invitation->shop->business_name }} to the list of businesses to which you have access - you'll find it under "Other Businesses" at the top left of your dashboard.</p>
        <a class="btn" href="https://pocket.com/invitations/{{ $invitation->token }}">Go to your dashboard</a>
        <br><br>
        <p>If you have any questions about this invitation, kindly contact {{ $invitation->by->first_name }} at {{ $invitation->by->email }}.</p>
        <br><br>
        <p>Warm regards, <br>Your friends at Pocket</p>
        <br>
        <hr>
        <p>P.S. Here's some more information about <a href="#">what Pocket is</a>, and if you have further questions, our friendly support team is on standby at
            <a href="#">help@pocket.com</a></p>
    </div>
    <div class="row footer">
        <p>&copy; Pocket 2020 <br>Modern Payments for Africa</p>
    </div>
</body>
</html>