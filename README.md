# AWS service Slack emojis

Slack emojis of AWS services using png assets posted at https://aws.amazon.com/architecture/icons/

## Usage

Install and run [slack-emoji-import](https://github.com/itslenny/slack-emoji-import) and specify following yaml URL

- With `aws-` prefix: <https://raw.githubusercontent.com/Mystik738/aws_emojipacks/master/aws-emojipacks.yml>
- Without prefix: <https://raw.githubusercontent.com/Mystik738/aws_emojipacks/master/noprefix-emojipacks.yml>

## Removing existing emojis

This is quite hacky, but slack-emoji-import doesn't delete existing emojis, so if you have any to overwrite you can execute the javascript code in the remove-existing.js files using the console while logged into {workspace}.slack.com/customize/emoji

## Update emojis

- Install required packages to your `virtualenv`: `pip install -U -r requirements.txt`
- Run `python make.py`
