import { newPasswordIsValid, confirmPasswordIsValid } from "../assets/js/shortcodes/tww-change-password-form-shortcode";

test('Returns false if new password equals current password', function() {
    expect(newPasswordIsValid('samepass', 'samepass')).toBe(false)
})

// Run the test
test('Returns false if new password does not equal current password', function () {
	expect(confirmPasswordIsValid('newpass', 'confirmpass')).toBe(false)
});
