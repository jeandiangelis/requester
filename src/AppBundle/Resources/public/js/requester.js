// var data = [
//     {id: 1, name: "www.google.com.br", status: "OK"},
//     {id: 2, name: "www.facebook.com", status: "OK"}
// ];

var UrlBox = React.createClass({
    loadUrlsFromServer: function() {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            cache: false,
            success: function(data) {
                this.setState({data: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.home, status, err.toString());
            }.bind(this)
        });
    },
    
    handleUrlsSubmit: function(url) {
        // var urls = this.state.data;
        alert(url);
        // url.id = Date.now();
        // var newUrls = urls.concat([url]);
        // this.setState({data: newUrls});

        $.ajax({
            url: this.props.url,
            dataType: 'json',
            type: 'POST',
            data: url,
            success: function(data) {
                this.setState({data: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },

    getInitialState: function() {
        return {data: []};
    },

    componentDidMount: function() {
        this.loadUrlsFromServer();
        setInterval(this.loadUrlsFromServer, this.props.interval);
    },

    render: function() {
        return (
            <div className="urlBox">
                <h1>Urls</h1>
                <UrlList data={this.state.data} />
                <UrlForm onUrlsSubmit={this.handleUrlsSubmit} />
            </div>
        );
    }
});

var Url = React.createClass({
    render: function() {
        return (
            <div className="url">
                <a href="#"> {this.props.name}</a>
                <p>{this.props.status}</p>
            </div>
        );
    }
});

var UrlList = React.createClass({
    render: function() {
        var urlNode = this.props.data.map(function(url) {
            return (
                <Url name={url.name} key={url.id} status={url.status}>

                </Url>
            );
        });
        return (
            <div className="urlList">
                {urlNode}
            </div>
        );
    }
});

var UrlForm = React.createClass({

    // getInitialState: function() {
    //     return {name: '', status: ''};
    // },

    handleSubmit: function(e) {
        e.preventDefault();

        var urls = this.state.urls;

        alert(urls);
        if (!urls) {
            return;
        }

        this.props.onUrlsSubmit({name: name});
        this.setState({name: '', status: ''});
    },

    handleUrlsChange: function(e) {
        this.setState({urls: e.target.value});
    },

    render: function() {
        return (
            <form className="urlForm" onSubmit={this.handleSubmit}>
                <textarea
                    name="urls"
                    id="urls"
                    cols="30"
                    rows="10"
                    onChange={this.handleUrlsChange}
                >
                </textarea>
                <input type="submit" value="Send requests" />
            </form>
        );
    }
});

ReactDOM.render(
    <UrlBox url="api/urls" interval={2000} />,
    document.getElementById('content')
);
