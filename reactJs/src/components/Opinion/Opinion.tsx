import React from 'react'

import { HeaderStar } from "../Header/Header"
import "./Opinion.scss"
import { boolean } from 'yup'


export const Opinion = ({ src, title, score = 0 }: { src: string, title: string, score?: number }) => {
    return (
        <div className="commentBox">
            <HeaderStar srcImg={src} title={title} score={score} />
            <div className="commentBody">
                <p className="text textComment">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dignissimos dolore ipsa eius obcaecati harum iure sed unde, ab fuga quo natus? Officiis blanditiis fuga consectetur voluptatem tempora perspiciatis, accusamus architecto.
                </p>
            </div>
            <p className="commentFooter text textComment">Cet avis vous a été utile? <span className="commentFooterValue">Oui</span> <span className="commentFooterValue">Non</span></p>
        </div>
    )
}



export const Note = () => {
    return (
        <div className="noteBox">
            <Bar title="Quality of service." value={4} />
            <Bar title="Compliance with deadlines." value={5} />
            <Bar title="Value for money" value={3} />
            <Bar title="Dealings between people" value={1} />
        </div>
    )
}


export const Bar = ({ title, value, status }: { title: string, value: number, status?: boolean }) => {

    let valeuAffiche;
    let color = 'red';
    let verdict = 'Mauvais';
    let barColor = 'redBackground';


    if (value !== 0) {
        valeuAffiche = (100 / 5) * value;
    }

    if (status === true) {
        color = 'green';
        verdict = 'Excellent';
        barColor = '';
    }

    return (

        <div className="barBox">
            <div style={{ display: "flex", flexDirection: "row", alignItems: "center", justifyContent: "space-between" }}>
                <div className="barTitle"><span style={{ display: "inline-block", width: "6ch", tabSize: "8" }}>{title} </span> : <text className='x'>{value * 10}</text> %</div>
                <div className={"barTitle " + color} >{verdict}</div>
            </div>
            <div className="barValue">
                <div className={"barValue  before " + barColor} style={{ width: valeuAffiche + "%" }}>
                </div>
            </div>
        </div>
    )
}