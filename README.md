<p align="center">
  <img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" alt="seven logo" />
</p>

<h1 align="center">seven SMS for IceHrm</h1>

<p align="center">
  Send bulk SMS and text-to-speech calls to your <a href="https://icehrm.com/">IceHrm</a> employees via the seven gateway.
</p>

<p align="center">
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-teal.svg" alt="MIT License" /></a>
  <img src="https://img.shields.io/badge/IceHrm-extension-blue" alt="IceHrm extension" />
  <img src="https://img.shields.io/badge/PHP-7.4%2B-purple" alt="PHP 7.4+" />
</p>

---

## Features

- **Bulk SMS** - Send messages to filtered groups of employees from the IceHrm admin
- **Bulk Voice Calls** - Place text-to-speech calls in the same workflow
- **Employee Filters** - Narrow recipients by *Status*, *Country*, *Job title* or *Employment status*

## Prerequisites

- [IceHrm](https://icehrm.com/) (self-hosted)
- A [seven account](https://www.seven.io/) with API key ([How to get your API key](https://help.seven.io/en/developer/where-do-i-find-my-api-key))

## Installation

1. Download the [latest release](https://github.com/seven-io/IceHrm/releases/latest/download/seven-icehrm-latest.zip).
2. Extract the archive into `/path/to/icehrm/extensions`.
3. Open **Administration > seven > Settings**.
4. Paste your seven API key and click **Submit**.

## Usage

### Send Bulk SMS

Open **seven > SMS**, fill in the form, choose your filters, then submit.

### Send Bulk Voice Calls

Open **seven > Voice**, fill in the form, choose your filters, then submit.

### Filters

| Filter | Description |
|--------|-------------|
| Status | Active / inactive employees |
| Country | Country of residence |
| Job title | Filter by job title field |
| Employment status | Permanent, contractor, etc. |

## Support

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact/) or [open an issue](https://github.com/seven-io/IceHrm/issues).

## License

[MIT](LICENSE)
