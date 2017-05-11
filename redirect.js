function redirect_to($location)
{
    if (!headers_sent($file, $line))
    {
        header("Location: " . $location);
    } else {
        printf("<script>location.href='%s';</script>", urlencode($location));
        # or deal with the problem
    }
    printf('<a href="%s">Moved</a>', urlencode($location));
    exit;
}