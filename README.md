# unvurn/http-user-agent

the simple parser for HTTP Request Header: "User-Agent"

## Install

`$ composer require unvurn/http-user-agent`

## Usage

```php
    // Controller
    
    // api function that processes request from UnityPlayer
    public function get(Request $request) {
        $userAgent = $request->getUserAgent();
        if (!$userAgent->hasProduct("UnityPlayer")) {
            throw new BadRequestException("access allowed for UnityPlayer only");
        }

        // regular process
        
        return response()->json([
            // ...        
        ]);
    }
```

```php
    // if you want to check "AppleWebKit" version:
    // ex) Windows 10/11
    //     Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36
    public function get(Request $request) {
        $userAgent = $request->getUserAgent();
        if ($userAgent->product("AppleWebKit")?->version !== "537.36") {
            throw new BadRequestException("latest AppleWebKit version required");
        }

        // regular process
        
        return response()->json([
            // ...        
        ]);
    }
```
