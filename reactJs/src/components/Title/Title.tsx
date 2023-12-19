import './Title.scss';

export const Title = ({ title, subtitle = null }: { title: string, subtitle?: string | null }) => {
    return (
        <div className="titleBox">
            <h1 className="title">
                {title}
            </h1>
            {subtitle && <h3 className="subTitle">{subtitle} </h3>}
        </div>
    )
}
