import { config } from "./config.js";
import { state } from "./state.js";

export const twwLoaderSVG = 'loader-rings-white';

export const loaderDefault = () => {  
    return createLoaderImage();
}

export const loaderGif = () => {
    return createGifLoader();
}

export const createLoaderImage = (pathname) => {
    pathname = pathname ?? config.loaders.default;

    const img = document.createElement('img');
    img.src = `${state.iconsPath}${pathname}.svg`;
    img.alt = 'Loading...';

    return img;
}

export const createGifLoader = (pathname) => {
    pathname = pathname ?? config.loaders.defaultGif;

    const img = document.createElement('img');
    img.src = `${state.iconsPath}${pathname}.gif`;
    img.alt = 'Loading...';
    img.width = 20;
    img.height = 20;
    img.classList.add('loadinggif');

    return img;
}
