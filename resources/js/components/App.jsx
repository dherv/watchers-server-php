import React, { Component } from "react";
import ReactDOM from "react-dom";
import Item from "./Item";
import styled from "styled-components";

export default class App extends Component {
    constructor(props) {
        super(props);
        this.state = {
            movies: [],
            ready: false
        };
    }
    componentDidMount() {
        axios.get(`/api/series`).then(response => {
            const movies = this.sortByDate(response);
            this.setState({ movies, ready: true });
        });
    }
    sortByDate(items) {
        return items.data.sort(
            (a, b) => new Date(b.release_date) - new Date(a.release_date)
        );
    }
    render() {
        // need to separate top and the rest to display the grid correctly
        const top = this.state.movies.slice(0, 2);
        const rest = this.state.movies.slice(2);
        return (
            this.state.ready && (
                <section className="container">
                    <div className="wrapper">
                        {top.map((item, index) => (
                            <Item item={item} index={index} type="top" />
                        ))}
                    </div>
                    <Grid className="wrapper">
                        {rest.map((item, index) => (
                            <Item item={item} index={index} type="rest" />
                        ))}
                    </Grid>
                </section>
            )
        );
    }
}

const Grid = styled.div`
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-gap: 1rem;
`;

ReactDOM.render(<App />, document.getElementById("app"));
