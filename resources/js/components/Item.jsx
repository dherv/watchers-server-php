import React, { PureComponent } from "react";
import styled from "styled-components";

export default class Item extends PureComponent {
    render() {
        const { item, index, type } = this.props;
        return (
            <Card key={item.title} index={index} type={type}>
                <div className="wrapper">
                    <Title className="title">{item.title}</Title>
                    <h5>{item.rating}</h5>
                    <small>{`${new Date(
                        item.release_date
                    ).getDate()} ${new Date(item.release_date).getMonth() +
                        1}`}</small>
                </div>
                {type == "top" && (
                    <img src={item.image_path.replace("@size", "w500")} />
                )}
                {type == "rest" && (
                    <img src={item.image_path.replace("@size", "w300")} />
                )}
            </Card>
        );
    }
}
const Card = styled.div`
    width: ${props => (props.type == "rest" ? "300px" : "500px")};
`;
const Title = styled.h3`
    font-weight: 600;
`;
