<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Activation Code</title>
</head>
<body>
<div class="container">
    <h2 style="color: #2dc96f">Hi {{$user->first_name}} {{$user->last_name}}</h2>
    <h2>Your account code</h2>
    <p>We emailed you the six digit code to {{$user->email}} <br/> Please copy the code below to restore your account with our website.</p>
    <div class="code-container">
        @foreach($data as $num)
        <button type="number" class="code" placeholder="0" min="0" max="9"  >{{$num}}</button>
        @endforeach
    </div>
</div>
</body>
</html>
<style>

    * {
        box-sizing: border-box;
    }

    body {
        background-color: #1DBF73;
        font-family: 'Muli', sans-serif;
        display: flex;
        flex-direction: row-reverse;
        align-items: center;
        justify-content: center;
        height: 100vh;
        overflow: hidden;
        margin: 0;
    }

    .container {
        background-color: #fff;
        border-radius: 10px;
        padding: 30px;
        max-width: 1100px;
        text-align: center;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }

    .code-container {
        display: flex;
        flex-direction: row-reverse;
        align-items: center;
        justify-content: center;
        margin: 40px 0;

    }

    .code {
        border-radius: 5px;
        font-size: 75px;
        height: 120px;
        width: 100px;
        border: 1px solid #eee;
        outline-width: thin;;
        outline-color: #ddd;
        margin: 1%;
        text-align: center;
        font-weight: 300;
        -moz-appearance: textfield;
        margin-left: 10px;
    }

    .code::-webkit-outer-spin-button,
    .code::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .code:valid {
        border-color: #1DBF73;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;
    }

    .info {
        background-color: #eaeaea;
        display: inline-block;
        padding: 10px;
        line-height: 20px;
        max-width: 400px;
        color: #777;
        border-radius: 5px;
    }

    @media (max-width: 600px) {
        .code-container {
            flex-wrap: wrap;
        }

        .code {
            font-size: 60px;
            height: 80px;
            max-width: 70px;
        }
    }
</style>
