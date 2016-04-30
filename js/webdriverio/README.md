# Setup

```bash
; install selenium-standalone
npm install selenium-standalone@latest -g
selenium-standalone install

cd path/to/elgg

; get webdriverio
npm install

cd js/webdriverio
cp -r config-dist config
```

Adjust the values in config/site.js

# Run Tests

In a separate Terminal window
```bash
selenium-standalone start
```

```bash
cd js/webdriverio

../../node_modules/.bin/wdio
```
