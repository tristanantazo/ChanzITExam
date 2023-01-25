# BACK-END EXAM

## Answer #1

```javascript
function recursive(parts, count, filters) {
    const part = parts[count];
    const split = part.split(":");
    const obj = {};

    obj[split[0]] = split[1].split(",");

    filters.push(obj);
    count = count + 1;
    if (count < parts.length) {
        return recursive(parts, count, filters);
    }
    return filters;
}

const parseFilterUrl = (url) => {
    const parts = url.split("|");
    const filters = [];
    return recursive(parts, 0, filters);
};

const filters = parseFilterUrl(
    "regions:the-north|people:hodor,the-hound|omg:true"
);
console.log(filters);
```

## Answer #2

First I will get the commit number or the previous copy before the fixes of the other team and then from that branch I will pushed my fixed and pushed it to live so that the fixes of the other team won’t be put in live branch, in that case i will inform them that the new live branch is my last pushed, they need to merge their committed branch to my previously live branch to live their fixes.

## #3 Explanation

Technology Stack.

1. VueJs
2. Laravel
3. Docker

I used docker for this project, but you can put it in a xampp sever and run like the usual laravel vuejs application

note that in number to word translation, it can translate upto two decimal places and for the word to number it can't translate the decimal number.

It will recognize the words that has no space but it cant translate characters and numbers.

only number to word can convert number to USD
