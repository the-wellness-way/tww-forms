// Import the function
import {sayHi} from '../assets/js/index.js';

// Run the test
test('Returns a greeting as a string', function () {
	expect(typeof sayHi()).toBe('string');
    expect(sayHi('Merlin').includes('Merlin')).toBe(true);
});
