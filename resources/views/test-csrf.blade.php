<!DOCTYPE html>
<html>
<head>
    <title>Test CSRF</title>
</head>
<body>
    <form action="/test-csrf" method="POST">
        @csrf
        <button type="submit">Test CSRF</button>
    </form>
</body>
</html>
