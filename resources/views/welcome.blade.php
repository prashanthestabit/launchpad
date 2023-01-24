<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('9b4a8055a4250f7a1a9d', {
      cluster: 'ap2'
    });
    console.log("Hello Done");
    var channel = pusher.subscribe('my-channel');
    console.log(channel);
    channel.bind('my-event', function(data) {
        console.log("Hello");
      alert(JSON.stringify(data));
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>
