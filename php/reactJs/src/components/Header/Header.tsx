import React, { useCallback } from 'react'
import "./Header.scss"


import { BiStar } from "react-icons/bi";

const __AvaterSquareClass = "avatar avatarSqare";
const __AvaterSquareMinClass = "avatar avatarSqareMin";

const __TitleClass = "title titleCard";
const __TitleLitle = "title titleComment";


export const Header = (
    {
        srcImg,
        title,
        subtitle,
        description,
        styleImage,
        styleContent,
        styleTitle,
        children
    }:
        {
            srcImg?: string,
            title?: string,
            subtitle?: string,
            description?: string
            styleImage?: string,
            styleContent?: string,
            styleTitle?: string,
            children?: any
        }) => {
    return (
        <div className={"headerCmp " + styleContent}>
            {
                srcImg &&
                <div className={"headerCmpAvatar"} >
                    <img src={srcImg} alt="" className={styleImage ? styleImage : __AvaterSquareClass} />
                </div>
            }
            <div className="headerCmpInfo">
                {
                    title &&
                    <div className={styleTitle ? styleTitle : __TitleClass}>
                        {title}
                    </div>
                }
                {
                    subtitle &&
                    <div className=" headerCmpSubTitle ">
                        {subtitle}
                    </div>
                }
                {
                    description &&
                    <div className="headerCmpDesc">
                        {description}
                    </div>
                }
                {
                    children &&
                    children
                }
            </div>
        </div>
    )
}







export const HeaderMedium = (
    {
        srcImg,
        title,
        subtitle,
        description,
        styleContent
    }:
        {
            srcImg?: string,
            title?: string,
            subtitle?: string,
            description?: string,
            styleContent?: string
        }) => {

    return (
        <Header srcImg={srcImg} title={title} subtitle={subtitle} description={description} styleContent={styleContent} />
    )
}



export const HeaderMini = (
    {
        srcImg,
        title,
        subtitle,
        description,
        styleContent
    }:
        {
            srcImg?: string,
            title?: string,
            subtitle?: string,
            description?: string,
            styleContent?: string
        }) => {

    return (
        <Header srcImg={srcImg} title={title} subtitle={subtitle} description={description} styleContent={styleContent} styleImage={__AvaterSquareMinClass} styleTitle={__TitleLitle} />
    )
}




export const HeaderStar = ({ srcImg, title, score = 0 }: { srcImg?: string, title?: string, score?: number }) => {
    const Star = useCallback(
        () => {

            let i = score;
            let result = [];

            for (let index = 1; index <= 5; index++) {
                i--;
                if (i >= 0) {
                    result.push(<BiStar color='#eab413' />)
                } else {
                    result.push(<BiStar color='#dbdbdb' />)
                }
            }

            return (

                <>
                    {result}
                </>
            )
        },
        [score],
    )


    return (
        <Header srcImg={srcImg} title={title} styleImage={__AvaterSquareMinClass} styleTitle={__TitleLitle}>
            <div className="HeaderStart">
                <Star />
            </div>
            <div className="headerCmpDesc">Le 05/06/2021</div>
        </Header>
    )
}