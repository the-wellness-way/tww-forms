## TWW Forms
A plugin that extends Memberpress's default functionality. 

## Requirements
* [Memberpress](https://memberpress.com/) - Used for subscription management.
* [Memberpress Developer Tools](https://memberpress.com/addons/developer-tools/) - Memberpess's API plugin.

### Shortcodes
`[tww_free_subscription]` - Use this shortcode to generate an email sign up tied to the memberpress product titled "Free Subscription"\
`[tww_current_membership]` - Use this shortcode to generate the current membership card. The cards state can be one of the following:
* `active` - active subscription that has not expired, and a valid last payment transaction (or is in the grace period)
* `canceled-but-active` - a canceled subscription that has not yet expired
* `canceled-and-expired` - a canceled subscription that has also passed the expire date
* `lapsed` - a failed transaction (maybe a faulty credit card) or no payment transactions
* `expired` - a subscription can technically expire though TWW+ Memberships never do
* `suspended` - a paused subscription (currently not in use)
* `no-subscription` - no current subscriptions for the member
