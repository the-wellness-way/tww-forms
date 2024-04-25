const loaderDefault = () => {  
    return createLoaderImage();
}

const loaderGif = () => {
    return createGifLoader();
}

const createLoaderImage = (pathname) => {
    pathname = pathname ?? config.loaders.default;

    const img = document.createElement('img');
    img.src = `${state.iconsPath}${pathname}.svg`;
    img.alt = 'Loading...';

    return img;
}

const createGifLoader = (pathname) => {
    pathname = pathname ?? config.loaders.defaultGif;

    const img = document.createElement('img');
    img.src = `${state.iconsPath}${pathname}.gif`;
    img.alt = 'Loading...';
    img.classList.add('loadinggif');

    return img;
}