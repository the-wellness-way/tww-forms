import { printConfig } from "../assets/js"; 

test('Returns a string', function () {
    expect(typeof printConfig()).toBe('string');
    expect(printConfig().includes('tww-edit-user-name')).toBe(true);
});