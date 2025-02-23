# preg-match
PregMatch is an encapsulation of preg_match returning a boolean instead of 0/1 and throwing an instance of PregMatchException in case of
an error.

The parameters for PregMatch::pregMatch() behaves the same as the parameters for PHP's built-in function preg_match() except for the absence
of the parameters "flags" and "offset".

## Usage
```
try {
    if (PregMatch::pregMatch($pattern, $subject, $matches)):
        // Code in case on a matched regular expression.
    else:
        // Code in case on a non-matched regular expression.
    endif;
} catch (PregMatchException $e) {
    // Error handling.
}
```
