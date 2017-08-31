# Changelog

This changelog references the relevant changes (bug and security fixes) done to `jomweb/billplz`.


## 1.0.0

Released: 2017-08-31

### New

* Initial stable release.

### Changes

* `Billplz\Three\Bill::create()` now support array for `$callbackUrl` parameter, when you can provide an associated array containing `callback_url` and `redirect_url`.
* Make `nesbot/carbon` dependency optional, simply use `new \DateTime()` if it is not presented.
