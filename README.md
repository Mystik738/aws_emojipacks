# AWS service Slack emojis

Slack emojis of AWS services using png assets posted at https://aws.amazon.com/architecture/icons/

## Usage

Install and run [slack-emoji-import-with-mfa](https://github.com/mystik738/slack-emoji-import-with-mfa) and specify following yaml URL

- With `aws-` prefix: <https://raw.githubusercontent.com/Mystik738/aws_emojipacks/master/aws-emojipacks.yml>
- Without prefix: <https://raw.githubusercontent.com/Mystik738/aws_emojipacks/master/noprefix-emojipacks.yml>

## Update emojis

- Install PHP.
- Download the PNG architecture icons at https://aws.amazon.com/architecture/icons/
- run php -f aws-emojis.php
- recommit to github